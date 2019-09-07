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
}
