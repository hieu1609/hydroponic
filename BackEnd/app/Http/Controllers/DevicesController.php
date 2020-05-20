<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use App\Devices;
use App\Sensors;

class DevicesController extends BaseApiController
{
    public function getDeviceIdForUser(Request $request)
    {
        /**
         * @SWG\Get(
         *     path="/devices/getDeviceIdForUser",
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
            $idDevices = Devices::getDeviceIdForUser($request->user->id);
            return $this->responseSuccess($idDevices);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function getSensorData(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/devices/getSensorData",
         *     description="get sensor data",
         *     tags={"Devices"},
         *     summary="get sensor data",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *          name="body",
         *          description="get sensor data by device id",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="devicesId",
         *                  type="integer",
         *              )
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
            $validator = Sensors::validate($request->all(), 'Get_Sensor_Data');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $checkDevices = Devices::where(['id' => $request->devicesId])->first();
            if (!$checkDevices) {
                return $this->responseErrorCustom("devices_id_not_found", 404);
            }
            else {
                if ($checkDevices->user_id != $request->user->id) {
                    return $this->responseErrorCustom("permission_denied", 403); //Forbidden
                }
                else {
                    $sensorData = Sensors::getSensorData($request->devicesId);
                    return $this->responseSuccess($sensorData);
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function getSensorDataChart(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/devices/getSensorDataChart",
         *     description="get sensor data for chart",
         *     tags={"Devices"},
         *     summary="get sensor data for chart",
         *     security={{"jwt":{}}},
         *      @SWG\Parameter(
         *          name="body",
         *          description="get sensor data by device id",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\Property(
         *                  property="devicesId",
         *                  type="integer",
         *              )
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
            $validator = Sensors::validate($request->all(), 'Get_Sensor_Data_Chart');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $checkDevices = Devices::where(['id' => $request->devicesId])->first();
            if (!$checkDevices) {
                return $this->responseErrorCustom("devices_id_not_found", 404);
            }
            else {
                if ($checkDevices->user_id != $request->user->id) {
                    return $this->responseErrorCustom("permission_denied", 403); //Forbidden
                }
                else {
                    $sensorData = Sensors::getSensorDataChart($request->devicesId);
                    $n = count($sensorData);
                    for ($i = 0; $i < $n; $i++) {
                        $sensorDataSort[$i] = $sensorData[$n - $i - 1];
                    }
                    return $this->responseSuccess($sensorDataSort);
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }
}
