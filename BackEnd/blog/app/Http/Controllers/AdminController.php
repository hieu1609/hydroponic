<?php

namespace App\Http\Controllers;

use App\User;
use App\Devices;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use JWTAuth;
use JWTFactory;
use Carbon\Carbon;

class AdminController extends BaseApiController
{

    public function deleteUser(Request $request)
    {
        /**
         * @SWG\Delete(
         *     path="/admin/{id}",
         *     description="Delete user",
         *     tags={"Admin"},
         *     summary="Delete user",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID user to delete",
         *         in="path",
         *         name="id",
         *         required=true,
         *         type="integer",
         *         format="int64"
         *     ),
         *      @SWG\Response(response=200, description="Successful"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=404, description="Not Found"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = User::validate(["userId" => $request->id], 'Rule_DeleteUser');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $userId = $request->id; //only for easy to under what is $request->id.
            $user = User::where(['id' => $userId])->first();
            if (!$user) {
                return $this->responseErrorCustom("users_not_found", 404);
            }

            $countAdmin = User::where(['admin' => 1])->count();
            if ($countAdmin <= 1 && $user->admin == true) {
                return $this->responseErrorCustom("can_not_delete_user", 403); //Forbidden
            }

            $user->delete();
            return $this->responseSuccess("Delete user successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function getAllUser(Request $request)
    {
        /**
         * @SWG\Get(
         *     path="/admin/all-user",
         *     description="Get list users",
         *     tags={"Admin"},
         *     summary="Get list users",
         *     security={{"jwt":{}}},
         *       @SWG\Parameter(
         *          name="page",
         *          description="Query page",
         *          in="query",
         *          type="number",
         *          required=true
         *      ),
         *       @SWG\Parameter(
         *          name="limit",
         *          description="Query limit records per page",
         *          in="query",
         *          type="number"
         *      ),
         *
         *      @SWG\Parameter(
         *          name="searchBy",
         *          description="Query searchBy column",
         *          in="query",
         *          type="string"
         *      ),
         *
         *      @SWG\Parameter(
         *          name="keyword",
         *          description="Query search",
         *          in="query",
         *          type="string"
         *      ),
         *
         *      @SWG\Parameter(
         *          name="sortBy",
         *          description="Query sortBy column",
         *          in="query",
         *          type="string"
         *      ),
         *
         *       @SWG\Parameter(
         *          name="sort",
         *          description="Query sort",
         *          in="query",
         *          type="string"
         *      ),
         *
         *       @SWG\Parameter(
         *          name="admin",
         *          description="Query admin",
         *          in="query",
         *          type="boolean"
         *      ),
         *
         *      @SWG\Response(response=200, description="Successful"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $input = $request->all();
            if (isset($input['admin'])) {
                $input['admin'] == 'true' ? $input['admin'] = 1 : $input['admin'] = 0;
            }

            $validator = User::validate($input, 'Rule_Get_All_User');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $results = User::funcGetAllUser($input);

            if ($results['error']) {
                return $this->responseErrorCustom($results['errorCode']);
            }
            return $this->responseSuccess($results['data']);

        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function getStatistic()
    {
        /**
         * @SWG\Get(
         *     path="/admin/statistic",
         *     description="Get Statistic",
         *     tags={"Admin"},
         *     summary="Get Statistic",
         *     security={{"jwt":{}}},
         *      @SWG\Response(response=200, description="Successful"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $result = [
                'account' => User::count(),
                'devices' =>  Devices::count(),
            ];

            return $this->responseSuccess($result);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function editUser(Request $request)
    {
        /**
         * @SWG\Put(
         *     path="/admin/{id}",
         *     description="Edit user",
         *     tags={"Admin"},
         *     summary="Edit user",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID user to edit",
         *         in="path",
         *         name="id",
         *         required=true,
         *         type="integer",
         *         format="int64"
         *     ),
         *      @SWG\Parameter(
         *          name="body",
         *          description="Create user",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="admin",
         *                  type="boolean",
         *              ),
         *              @SWG\Property(
         *                  property="username",
         *                  type="string",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=404, description="Not Found"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $input = $request->all();
            $input['userId'] = $request->id;

            $validator = User::validate($input, 'Rule_EditUser');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $userId = $request->id;
            $user = User::where(['id' => $userId])->first();
            if (!$user) {
                return $this->responseErrorCustom("users_not_found", 404);
            }

            $countAdmin = User::where(['admin' => 1])->count();
            if ($countAdmin <= 1 && $request->admin == false && $user->admin == true) {
                return $this->responseErrorCustom("can_not_edit_user", 403); //min number of admin is 1
            }

            $request->admin ?  $user->admin = 1 : $user->admin = 0;
            $user->username = $request->username;
            $user->save();
            return $this->responseSuccess($user);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }
}
