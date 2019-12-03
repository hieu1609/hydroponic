<?php

namespace App\Http\Controllers;

use App\User;
use App\Devices;
use App\Notification;
use App\Nutrients;
use App\PpmAutomatic;
use App\PumpAutomatic;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use JWTAuth;
use JWTFactory;
use Carbon\Carbon;

class AdminController extends BaseApiController
{
    public function getUserAdmin(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/getUserAdmin",
         *     description="Get user",
         *     tags={"Admin"},
         *     summary="Get user",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *          name="body",
         *          description="Get user",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="page",
         *                  type="integer",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = User::validate($request->all(), 'Get_User_Admin');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $result['data'] = User::getUserAdmin($request->page);
            $result['numPage'] = ceil(User::count()/10);
            $result['total'] = User::count();
            return $this->responseSuccess($result);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function addUser(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/addUser",
         *     description="Add user",
         *     tags={"Admin"},
         *     summary="Add user",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *          name="body",
         *          description="Add user",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="username",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="email",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="password",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="city",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="admin",
         *                  type="boolean",
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
            $validator = User::validate($request->all(), 'Rule_AddUser');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $city = $request->city;
            if($request->city === null) {
                $city = "Ho Chi Minh City";
            }

            $user = new User;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->city = $city;
            $user->admin = $request->admin;
            $user->save();
            return $this->responseSuccess("Add User successfully");
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
         *          description="Edit user",
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

    public function getNotificationsAdmin(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/getNotificationsAdmin",
         *     description="Get notifications",
         *     tags={"Admin"},
         *     summary="Get notifications",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *          name="body",
         *          description="Get notifications",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="page",
         *                  type="integer",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = Notification::validate($request->all(), 'Get_Notifications_Admin');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $result['data'] = Notification::getNotificationsAdmin($request->page);
            $result['numPage'] = ceil(Notification::count()/10);
            $result['total'] = Notification::count();
            return $this->responseSuccess($result);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function sendNotification(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/sendNotification",
         *     description="Send notification for user",
         *     tags={"Admin"},
         *     summary="Send notification",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Send notification for user",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="userId",
         *                  type="integer",
         *              ),
         *              @SWG\property(
         *                  property="notificationTitle",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="notificationContent",
         *                  type="string",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = Notification::validate($request->all(), 'Send_Notification');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            
            $checkId = User::where(['id' => $request->userId])->first();
            if (!$checkId) {
                return $this->responseErrorCustom("id_not_found", 404);
            }

            if ($request->userId === $request->user->id) {
                return $this->responseErrorCustom("that_is_your_id", 403);
            }

            $notification = new Notification;
            $notification->user_id_send = 1;
            $notification->user_id_receive = $request->userId;
            $notification->title = $request->notificationTitle;
            $notification->content = $request->notificationContent;
            $notification->save();
            return $this->responseSuccess("Send notification successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function sendNotificationForAllUsers(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/sendNotificationForAllUsers",
         *     description="Send notification for all users",
         *     tags={"Admin"},
         *     summary="Send notification",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Send notification for all users",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="notificationTitle",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="notificationContent",
         *                  type="string",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = Notification::validate($request->all(), 'Send_Notification_All_Users');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $allUserId = User::getUserId();
            for ($i = 0; $i < count($allUserId); $i++) {
                $notification = new Notification;
                $notification->user_id_send = 1;
                $notification->user_id_receive = $allUserId[$i]->id;
                $notification->title = $request->notificationTitle;
                $notification->content = $request->notificationContent;
                $notification->save();
            }
            return $this->responseSuccess("Send notification for all users successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function editNotification(Request $request)
    {
        /**
         * @SWG\Put(
         *     path="/admin/notification/{notificationId}",
         *     description="Edit notification",
         *     tags={"Admin"},
         *     summary="Edit notification",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID notification to edit",
         *         in="path",
         *         name="notificationId",
         *         required=true,
         *         type="integer",
         *         format="int64"
         *     ),
         *      @SWG\Parameter(
         *          name="body",
         *          description="Edit notification",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="userIdSend",
         *                  type="integer",
         *              ),
         *              @SWG\Property(
         *                  property="userIdReceive",
         *                  type="integer",
         *              ),
         *              @SWG\Property(
         *                  property="notificationTitle",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="notificationContent",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="seen",
         *                  type="boolean",
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
            $input['notificationId'] = $request->notificationId;
            $validator = Notification::validate($input, 'Edit_Notification');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $checkUserIdSend = User::where(['id' => $request->userIdSend])->first();
            if (!$checkUserIdSend) {
                return $this->responseErrorCustom("user_id_send_not_found", 404);
            }

            $checkUserIdReceive = User::where(['id' => $request->userIdReceive])->first();
            if (!$checkUserIdReceive) {
                return $this->responseErrorCustom("user_id_receive_not_found", 404);
            }
            
            $checkNotification = Notification::where(['id' => $request->notificationId])->first();
            if (!$checkNotification) {
                return $this->responseErrorCustom("notification_id_not_found", 404);
            }

            $checkNotification->user_id_send = $request->userIdSend;
            $checkNotification->user_id_receive = $request->userIdReceive;
            $checkNotification->title = $request->notificationTitle;
            $checkNotification->content = $request->notificationContent;
            $checkNotification->seen = $request->seen;
            $checkNotification->save();
            return $this->responseSuccess($checkNotification);

        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function deleteNotification(Request $request)
    {
        /**
         * @SWG\Delete(
         *     path="/admin/notification/{notificationId}",
         *     description="Delete notification",
         *     tags={"Admin"},
         *     summary="Delete notification",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID notification to delete",
         *         in="path",
         *         name="notificationId",
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
            $input['notificationId'] = $request->notificationId;
            $validator = Notification::validate($input, 'Delete_Notification');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $checkNotification = Notification::where(['id' => $request->notificationId])->first();
            if (!$checkNotification) {
                return $this->responseErrorCustom("notification_id_not_found", 404);
            }

            $checkNotification->delete();
            return $this->responseSuccess("Delete notification successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function getDevicesAdmin(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/getDevicesAdmin",
         *     description="Get devices",
         *     tags={"Admin"},
         *     summary="Get devices",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *          name="body",
         *          description="Get devices",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="page",
         *                  type="integer",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = Devices::validate($request->all(), 'Get_Devices_Admin');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $result['data'] = Devices::getDevicesAdmin($request->page);
            $result['numPage'] = ceil(Devices::count()/10);
            $result['total'] = Devices::count();
            return $this->responseSuccess($result);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function addDevice(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/addDevice",
         *     description="Add devices for user",
         *     tags={"Admin"},
         *     summary="Add devices",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Add devices for user",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="userId",
         *                  type="integer",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = Devices::validate($request->all(), 'Add_Devices');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            
            $checkId = User::where(['id' => $request->userId])->first();
            if (!$checkId) {
                return $this->responseErrorCustom("id_not_found", 404);
            }

            $devices = new Devices;
            $devices->user_id = $request->userId;
            $devices->save();

            $devicesID = Devices::getIdNewDevice();

            $ppmAuto = new PpmAutomatic;
            $ppmAuto->device_id = $devicesID[0]->id;
            $ppmAuto->nutrient_id = 2;
            $ppmAuto->save();

            $pumpAuto = new PumpAutomatic;
            $pumpAuto->device_id = $devicesID[0]->id;
            $pumpAuto->save();
            return $this->responseSuccess("Add device successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function editDevices(Request $request)
    {
        /**
         * @SWG\Put(
         *     path="/admin/devices/{devicesId}",
         *     description="Edit devices",
         *     tags={"Admin"},
         *     summary="Edit devices",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID devices to edit",
         *         in="path",
         *         name="devicesId",
         *         required=true,
         *         type="integer",
         *         format="int64"
         *     ),
         *      @SWG\Parameter(
         *          name="body",
         *          description="Edit devices",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="userId",
         *                  type="integer",
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
            $input['devicesId'] = $request->devicesId;
            $validator = Devices::validate($input, 'Edit_Devices');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $checkId = User::where(['id' => $request->userId])->first();
            if (!$checkId) {
                return $this->responseErrorCustom("id_not_found", 404);
            }
            
            $checkDevices = Devices::where(['id' => $request->devicesId])->first();
            if (!$checkDevices) {
                return $this->responseErrorCustom("devices_id_not_found", 404);
            }

            $checkDevices->user_id = $request->userId;
            $checkDevices->save();
            return $this->responseSuccess($checkDevices);

        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function deleteDevices(Request $request)
    {
        /**
         * @SWG\Delete(
         *     path="/admin/devices/{devicesId}",
         *     description="Delete device",
         *     tags={"Admin"},
         *     summary="Delete device",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID device to delete",
         *         in="path",
         *         name="devicesId",
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
            $input['devicesId'] = $request->devicesId;
            $validator = Devices::validate($input, 'Delete_Devices');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $checkDevices = Devices::where(['id' => $request->devicesId])->first();
            if (!$checkDevices) {
                return $this->responseErrorCustom("devices_id_not_found", 404);
            }
            $checkPpmDevices = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
            $checkPumpDevices = PumpAutomatic::where(['device_id' => $request->devicesId])->first();

            $checkPpmDevices->delete();
            $checkPumpDevices->delete();
            $checkDevices->delete();
            return $this->responseSuccess("Delete device successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function getNutrientsAdmin(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/getNutrientsAdmin",
         *     description="Get nutrients",
         *     tags={"Admin"},
         *     summary="Get nutrients",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *          name="body",
         *          description="Get nutrients",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="page",
         *                  type="integer",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = Nutrients::validate($request->all(), 'Get_Nutrients_Admin');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $result['data'] = Nutrients::getNutrientsAdmin($request->page);
            $result['numPage'] = ceil(Nutrients::count()/10);
            $result['total'] = Nutrients::count();
            return $this->responseSuccess($result);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function addNutrient(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/admin/addNutrient",
         *     description="Add nutrient",
         *     tags={"Admin"},
         *     summary="Add nutrient",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Add nutrient",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="userId",
         *                  type="integer",
         *              ),
         *              @SWG\property(
         *                  property="plantName",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="ppmMin",
         *                  type="integer",
         *              ),
         *              @SWG\property(
         *                  property="ppmMax",
         *                  type="integer",
         *              ),
         *          ),
         *      ),
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=403, description="Forbidden"),
         *      @SWG\Response(response=422, description="Unprocessable Entity"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $validator = Nutrients::validate($request->all(), 'Add_Nutrient');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            if ($request->ppmMax - $request->ppmMin < 100) {
                return $this->responseErrorCustom("ppmMax_must_be_greater_than_ppmMin_100", 403); //Forbidden
            }

            $nutrient = new Nutrients;
            $nutrient->user_id = $request->userId;
            $nutrient->plant_name = $request->plantName;
            $nutrient->ppm_min = $request->ppmMin;
            $nutrient->ppm_max = $request->ppmMax;
            $nutrient->save();

            return $this->responseSuccess($nutrient);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function editNutrient(Request $request)
    {
        /**
         * @SWG\Put(
         *     path="/admin/nutrient/{nutrientId}",
         *     description="Edit nutrient",
         *     tags={"Admin"},
         *     summary="Edit nutrient",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID nutrient to edit",
         *         in="path",
         *         name="nutrientId",
         *         required=true,
         *         type="integer",
         *         format="int64"
         *     ),
         *      @SWG\Parameter(
         *          name="body",
         *          description="Edit nutrient",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="userId",
         *                  type="integer",
         *              ),
         *              @SWG\Property(
         *                  property="plantName",
         *                  type="string",
         *              ),
         *              @SWG\Property(
         *                  property="ppmMin",
         *                  type="integer",
         *              ),
         *              @SWG\Property(
         *                  property="ppmMax",
         *                  type="integer",
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
            $input['nutrientId'] = $request->nutrientId;
            $validator = Nutrients::validate($input, 'Edit_Nutrient');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            if ($request->ppmMax - $request->ppmMin < 50) {
                return $this->responseErrorCustom("ppmMax_must_be_greater_than_ppmMin_100", 403); //Forbidden
            }

            $checkUserId = User::where(['id' => $request->userId])->first();
            if (!$checkUserId) {
                return $this->responseErrorCustom("user_id_not_found", 404);
            }

            $checkNutrientId = Nutrients::where(['id' => $request->nutrientId])->first();
            if (!$checkNutrientId) {
                return $this->responseErrorCustom("nutrient_id_not_found", 404);
            } 

            $checkNutrientId->user_id = $request->userId;
            $checkNutrientId->plant_name = $request->plantName;
            $checkNutrientId->ppm_min = $request->ppmMin;
            $checkNutrientId->ppm_max = $request->ppmMax;
            $checkNutrientId->save();
            return $this->responseSuccess($checkNutrientId);

        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function deleteNutrient(Request $request)
    {
        /**
         * @SWG\Delete(
         *     path="/admin/nutrient/{nutrientId}",
         *     description="Delete nutrient",
         *     tags={"Admin"},
         *     summary="Delete nutrient",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID nutrient to delete",
         *         in="path",
         *         name="nutrientId",
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
            $input['nutrientId'] = $request->nutrientId;
            $validator = Nutrients::validate($input, 'Delete_Nutrient');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $checkNutrientId = Nutrients::where(['id' => $request->nutrientId])->first();
            if (!$checkNutrientId) {
                return $this->responseErrorCustom("nutrient_id_not_found", 404);
            } 

            $checkNutrient->delete();
            return $this->responseSuccess("Delete nutrient successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }
}
