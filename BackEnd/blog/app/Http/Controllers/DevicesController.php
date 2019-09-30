<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use App\Devices;

class DevicesController extends BaseApiController
{
    public function getData(Request $request)
    {
        /**
         * @SWG\Get(
         *     path="/devices/getData",
         *     description="get data",
         *     tags={"Devices"},
         *     summary="get data",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $dataDevices = Devices::getData($request->user->id);
            return $this->responseSuccess($dataDevices);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function pumpDevices(Request $request)
    {
        /**
         * @SWG\Put(
         *     path="/devices/{devicesId}",
         *     description="Turn on/off pump devices",
         *     tags={"Devices"},
         *     summary="Turn on/off pump devices",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *         description="ID device",
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
         *                  property="pump",
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
            $input['devicesId'] = $request->devicesId;
            $validator = Devices::validate($input, 'Pump_Devices');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            
            $checkDevices = Devices::where(['id' => $request->devicesId])->first();
            if (!$checkDevices) {
                return $this->responseErrorCustom("devices_id_not_found", 404);
            }
            else {
                if($checkDevices->user_id != $request->user->id) {
                    return $this->responseErrorCustom("permission_denied", 403); //Forbidden
                }
                else {
                    $checkDevices->pump = $request->pump;
                    $checkDevices->save();
                    return $this->responseSuccess($checkDevices);
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }
}
