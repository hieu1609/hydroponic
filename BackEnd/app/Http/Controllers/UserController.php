<?php

namespace App\Http\Controllers;

use Salman\Mqtt\MqttClass\Mqtt;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use App\Notification;
use App\Nutrients;
use App\PumpAutomatic;
use App\PpmAutomatic;
use App\Devices;
use App\Sensors;

class UserController extends BaseApiController
{
    public function getNotifications(Request $request)
    {
        /**
         * @SWG\Get(
         *     path="/user/getNotifications",
         *     description="get notifications",
         *     tags={"User"},
         *     summary="get notifications",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $dataNotifications = Notification::getNotifications($request->user->id);
            return $this->responseSuccess($dataNotifications);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function postFeedback(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/user/postFeedback",
         *     description="Send feedback for admin",
         *     tags={"User"},
         *     summary="Send feedback",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Send feedback for admin",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="feedbackTitle",
         *                  type="string",
         *              ),
         *              @SWG\property(
         *                  property="feedbackContent",
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
            $validator = Notification::validate($request->all(), 'Post_Feedback');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            
            $feedback = new Notification;
            $feedback->user_id_send = $request->user->id;
            $feedback->user_id_receive = 1;
            $feedback->title = $request->feedbackTitle;
            $feedback->content = $request->feedbackContent;
            $feedback->save();
            return $this->responseSuccess("Send feedback successfully");

        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    public function seenNotification(Request $request)
    {
        /**
         * @SWG\Put(
         *     path="/user/seenNotification",
         *     description="Seen notification",
         *     tags={"User"},
         *     summary="Seen notification",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Seen notification",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
         *              @SWG\property(
         *                  property="notificationId",
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
            $validator = Notification::validate($request->all(), 'Seen_Notification');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $checkNotificationId = Notification::where([['user_id_receive', $request->user->id],['id', $request->notificationId]])->first();
            if (!$checkNotificationId) {
                return $this->responseErrorCustom("notification_id_not_found", 404);
            }
            else {
                $checkNotificationId->update(['seen' => true]);
                return $this->responseSuccess("Seen notification successfully");
            }


        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/controlPump",
     *     description="Control pump via mqtt",
     *     tags={"Pump"},
     *     summary="Control pump via mqtt",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Control pump via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="message",
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
    public function controlPump(Request $request)
    {
        try {
            $validator = Notification::validate($request->all(), 'Control_Pump');
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
                    $checkAuto = PumpAutomatic::where(['device_id' => $request->devicesId])->first();
                    if (!$checkAuto) {
                        return $this->responseErrorCustom("devices_id_not_found", 404);
                    }

                    if ($checkAuto->auto == 1) {
                        // $checkAuto->auto = 0;
                        // $checkAuto->save();
                        $mess = "0=".$checkAuto->time_on."=".$checkAuto->time_off;
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=pumpAuto";
                        $output = $mqtt->ConnectAndPublish($topic, $mess);
                        sleep(1);
                    }

                    $mqtt = new Mqtt();
                    $topic = "thuycanhiot@gmail.com/".$request->devicesId."=pump";
                    $output = $mqtt->ConnectAndPublish($topic, $request->message);
                    if ($output === true) {
                        return $this->responseSuccess("Seen pump control via mqtt successfully");
                    } 
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/controlWaterIn",
     *     description="Control water in via mqtt",
     *     tags={"Water"},
     *     summary="Control water in via mqtt",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Control water in via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="message",
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
    public function controlWaterIn(Request $request)
    {
        try {
            $validator = Notification::validate($request->all(), 'Control_Water_In');
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
                    $sensorData = Sensors::getSensorData($request->devicesId);
                    if (!$sensorData) {
                        return $this->responseErrorCustom("devices_id_not_found_sensors_table", 404);
                    }

                    $checkAuto = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                    if (!$checkAuto) {
                        return $this->responseErrorCustom("devices_id_not_found", 404);
                    }
                    else {
                        // $checkAuto->auto_mode = 0;
                        // $checkAuto->auto_status = 0;
                        // $checkAuto->save();
                        $mess = "0=".$checkAuto->nutrient_id;
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=ppmAuto";
                        $output = $mqtt->ConnectAndPublish($topic, $mess);
                    }

                    if($request->message == 1){
                        if ($sensorData[0]->water < 80) {
                            $mqtt = new Mqtt();
                            $topic = "thuycanhiot@gmail.com/".$request->devicesId."=waterIn";
                            $output = $mqtt->ConnectAndPublish($topic, $request->message);
                            if ($output === true) {
                                return $this->responseSuccess("Seen water in control via mqtt successfully");
                            } 
                        }
                        else {
                            return $this->responseSuccess("Auto off water in");
                        }
                    }
                    else {
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=waterIn";
                        $output = $mqtt->ConnectAndPublish($topic, $request->message);
                        if ($output === true) {
                            return $this->responseSuccess("Seen water in control via mqtt successfully");
                        } 
                    }
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/checkWaterIn",
     *     description="check water in after 80%",
     *     tags={"Water"},
     *     summary="Control water in via mqtt",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Control water in via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
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
    public function checkWaterIn(Request $request)
    {
        try {
            set_time_limit(0);
            $validator = Notification::validate($request->all(), 'Check_Water_In');
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
                    $sensorData = Sensors::getSensorData($request->devicesId);
                    if (!$sensorData) {
                        return $this->responseErrorCustom("devices_id_not_found_sensors_table", 404);
                    }

                    while ($sensorData[0]->water_in == 1 && $sensorData[0]->water < 80) {
                        sleep(3);
                        $sensorData = Sensors::getSensorData($request->devicesId);
                    }
                    $mqtt = new Mqtt();
                    $topic = "thuycanhiot@gmail.com/".$request->devicesId."=waterIn";
                    $output = $mqtt->ConnectAndPublish($topic, 0);
                    if ($output === true) {
                        return $this->responseSuccess("Auto off water in");
                    } 
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/controlWaterOut",
     *     description="Control water out via mqtt",
     *     tags={"Water"},
     *     summary="Control water out via mqtt",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Control water out via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="message",
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
    public function controlWaterOut(Request $request)
    {
        try {
            $validator = Notification::validate($request->all(), 'Control_Water_Out');
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
                    $sensorData = Sensors::getSensorData($request->devicesId);
                    if (!$sensorData) {
                        return $this->responseErrorCustom("devices_id_not_found_sensors_table", 404);
                    }

                    $checkAuto = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                    if (!$checkAuto) {
                        return $this->responseErrorCustom("devices_id_not_found", 404);
                    }
                    else {
                        // $checkAuto->auto_mode = 0;
                        // $checkAuto->auto_status = 0;
                        // $checkAuto->save();
                        $mess = "0=".$checkAuto->nutrient_id;
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=ppmAuto";
                        $output = $mqtt->ConnectAndPublish($topic, $mess);
                    }

                    if($request->message == 1){
                        if ($sensorData[0]->water > 20) {
                            $mqtt = new Mqtt();
                            $topic = "thuycanhiot@gmail.com/".$request->devicesId."=waterOut";
                            $output = $mqtt->ConnectAndPublish($topic, $request->message);
                            if ($output === true) {
                                return $this->responseSuccess("Seen water out control via mqtt successfully");
                            } 
                        }
                        else {
                            return $this->responseSuccess("Auto off water out");
                        }
                    }
                    else {
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=waterOut";
                        $output = $mqtt->ConnectAndPublish($topic, $request->message);
                        if ($output === true) {
                            return $this->responseSuccess("Seen water out control via mqtt successfully");
                        } 
                    }

                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/checkWaterOut",
     *     description="check water out before 20%",
     *     tags={"Water"},
     *     summary="Control water out via mqtt",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Control water out via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
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
    public function checkWaterOut(Request $request)
    {
        try {
            set_time_limit(0);
            $validator = Notification::validate($request->all(), 'Check_Water_Out');
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
                    $sensorData = Sensors::getSensorData($request->devicesId);
                    if (!$sensorData) {
                        return $this->responseErrorCustom("devices_id_not_found_sensors_table", 404);
                    }

                    while ($sensorData[0]->water_out == 1 && $sensorData[0]->water > 20) {
                        sleep(3);
                        $sensorData = Sensors::getSensorData($request->devicesId);
                    }
                    $mqtt = new Mqtt();
                    $topic = "thuycanhiot@gmail.com/".$request->devicesId."=waterOut";
                    $output = $mqtt->ConnectAndPublish($topic, 0);
                    if ($output === true) {
                        return $this->responseSuccess("Auto off water out");
                    } 
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/controlMix",
     *     description="Control mix via mqtt",
     *     tags={"Mix"},
     *     summary="Control mix via mqtt",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Control mix via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="message",
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
    public function controlMix(Request $request)
    {
        try {
            $validator = Notification::validate($request->all(), 'Control_Mix');
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
                    $sensorData = Sensors::getSensorData($request->devicesId);
                    if (!$sensorData) {
                        return $this->responseErrorCustom("devices_id_not_found_sensors_table", 404);
                    }
                    $mqtt = new Mqtt();
                    $topic = "thuycanhiot@gmail.com/".$request->devicesId."=mix";
                    $output = $mqtt->ConnectAndPublish($topic, $request->message);
                    if ($output === true) {
                        return $this->responseSuccess("Seen mix control via mqtt successfully");
                    } 
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/controlPpm",
     *     description="Control ppm via mqtt",
     *     tags={"Ppm"},
     *     summary="Send topic ppm & message 1",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Control ppm via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
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
    public function controlPpm(Request $request)
    {
        try {
            $validator = Notification::validate($request->all(), 'Control_Ppm');
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
                    $sensorData = Sensors::getSensorData($request->devicesId);
                    if (!$sensorData) {
                        return $this->responseErrorCustom("devices_id_not_found_sensors_table", 404);
                    }

                    $checkAuto = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                    if (!$checkAuto) {
                        return $this->responseErrorCustom("devices_id_not_found", 404);
                    }
                    else {
                        // $checkAuto->auto_mode = 0;
                        // $checkAuto->auto_status = 0;
                        // $checkAuto->save();
                        $mess = "0=".$checkAuto->nutrient_id;
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=ppmAuto";
                        $output = $mqtt->ConnectAndPublish($topic, $mess);
                    }
                    $mqtt = new Mqtt();
                    $topic = "thuycanhiot@gmail.com/".$request->devicesId."=ppm";
                    $output = $mqtt->ConnectAndPublish($topic, 1);
                    if ($output === true) {
                        return $this->responseSuccess("Seen ppm control via mqtt successfully");
                    } 
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/sendMsgViaMqtt",
     *     description="Send msg via mqtt",
     *     tags={"MQTT"},
     *     summary="Send msg via mqtt",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Send msg via mqtt",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="topic",
     *                  type="string",
     *              ),
     *              @SWG\property(
     *                  property="message",
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
    public function sendMsgViaMqtt(Request $request)
    {
        try {
            $validator = Notification::validate($request->all(), 'Send_Msg_Via_Mqtt');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $mqtt = new Mqtt();
            $topic = "thuycanhiot@gmail.com/".$request->devicesId."=".$request->topic;
            $output = $mqtt->ConnectAndPublish($topic, $request->message);
            if ($output === true) {
                return $this->responseSuccess("Seen to mqtt successfully");
            } 
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/subscribetoTopic",
     *     description="Subscribe to topic",
     *     tags={"MQTT"},
     *     summary="Subscribe to topic",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Subscribe to topic",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="topic",
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
    public function subscribetoTopic(Request $request)
    {
        try {
            set_time_limit(0);
            $validator = Notification::validate($request->all(), 'Subscribe_To_Topic');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }
            $topic = "thuycanhiot@gmail.com/".$request->devicesId."=".$request->topic;
            $mqtt = new Mqtt();
            $msg = "";
            $mqtt->ConnectAndSubscribe($topic, function($topic, $message){
                if($message != "") {
                    $newTopic = $topic."=".$message;
                    exit($newTopic);
                }
            });
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }

    }

    public function getNutrients(Request $request)
    {
        /**
         * @SWG\Get(
         *     path="/user/getNutrients",
         *     description="get nutrients",
         *     tags={"User"},
         *     summary="get nutrients",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Response(response=200, description="Successful operation"),
         *      @SWG\Response(response=401, description="Unauthorized"),
         *      @SWG\Response(response=500, description="Internal Server Error"),
         * )
         */

        try {
            $dataNutrients = Nutrients::getNutrients($request->user->id);
            return $this->responseSuccess($dataNutrients);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    public function postNutrient(Request $request)
    {
        /**
         * @SWG\Post(
         *     path="/user/postNutrient",
         *     description="Post nutrient",
         *     tags={"User"},
         *     summary="Post nutrient",
         *     security={{"jwt":{}}},
         *
         *      @SWG\Parameter(
         *          name="body",
         *          description="Post nutrient",
         *          required=true,
         *          in="body",
         *          @SWG\Schema(
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
            $validator = Nutrients::validate($request->all(), 'Post_Nutrient');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            if ($request->ppmMax - $request->ppmMin < 100) {
                return $this->responseErrorCustom("ppmMax_must_be_greater_than_ppmMin_100", 403); //Forbidden
            }

            $nutrient = new Nutrients;
            $nutrient->user_id = $request->user->id;
            $nutrient->plant_name = $request->plantName;
            $nutrient->ppm_min = $request->ppmMin;
            $nutrient->ppm_max = $request->ppmMax;
            $nutrient->save();
            return $this->responseSuccess($nutrient);

        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/pumpAutoOn",
     *     description="Turn on pump auto mode",
     *     tags={"Pump"},
     *     summary="Turn on pump auto mode",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Turn on pump auto mode",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="timeOn",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="timeOff",
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
    public function pumpAutoOn(Request $request)
    {
        try {
            set_time_limit(0);
            $validator = PumpAutomatic::validate($request->all(), 'Pump_Auto_On');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            if($request->timeOn <= 0 or $request->timeOff <= 0) {
                return $this->responseErrorCustom("time_must_be_greater_than_0", 403); //Forbidden
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
                    $checkAuto = PumpAutomatic::where(['device_id' => $request->devicesId])->first();
                    if (!$checkAuto) {
                        return $this->responseErrorCustom("devices_id_not_found", 404);
                    }
                    else {
                        // $checkAuto->time_on = $request->timeOn;
                        // $checkAuto->time_off = $request->timeOff;
                        // $checkAuto->auto = 1;
                        // $checkAuto->save();
                        $mess = "1=".$request->timeOn."=".$request->timeOff;
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=pumpAuto";
                        $output = $mqtt->ConnectAndPublish($topic, $mess);
                        if ($output === true) {
                            return $this->responseSuccess("Seen turn on auto mode successfully");
                        } 
                    }
                }
            }

            // $topic = "thuycanhiot@gmail.com/".$request->devicesId."=pump";
            // $message = 1;
            // $mqtt = new Mqtt();
            // $getAutoPump = PumpAutomatic::getAuto($request->devicesId);
            // while($getAutoPump[0]->auto == 1){   
            //     $output = $mqtt->ConnectAndPublish($topic, $message);
            //     if($message == 1) {
            //         $message = 0;
            //         $n = 1;
            //         for($i = 0; $i < $n; $i++) { 
            //             $getAutoPump = PumpAutomatic::getAuto($request->devicesId);
            //             if($getAutoPump[0]->auto == 1 and $n <= $request->timeOn*60) {
            //                 $n++;
            //                 sleep(1);
            //             }
            //         }
            //     }
            //     else {
            //         $message = 1;
            //         $n = 1;
            //         for($i = 0; $i < $n; $i++) { 
            //             $getAutoPump = PumpAutomatic::getAuto($request->devicesId);
            //             if($getAutoPump[0]->auto == 1 and $n <= $request->timeOff*60) {
            //                 $n++;
            //                 sleep(1);
            //             }
            //         }
            //     }    
            // }
            
            // if ($output === true) {
            //     return $this->responseSuccess("Seen turn on auto mode successfully");
            // } 
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/pumpAutoOff",
     *     description="Turn off pump auto mode",
     *     tags={"Pump"},
     *     summary="Turn off pump auto mode",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Turn off pump auto mode",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
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
    public function pumpAutoOff(Request $request)
    {
        try {
            $validator = PumpAutomatic::validate($request->all(), 'Pump_Auto_Off');
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
                    $checkAuto = PumpAutomatic::where(['device_id' => $request->devicesId])->first();
                    if (!$checkAuto) {
                        return $this->responseErrorCustom("devices_id_not_found", 404);
                    }
                    else {
                        // $checkAuto->auto = 0;
                        // $checkAuto->save();
                        $mqtt = new Mqtt();       
                        $mess = "0=".$checkAuto->time_on."=".$checkAuto->time_off;
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=pumpAuto";
                        $output = $mqtt->ConnectAndPublish($topic, $mess);
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=pump";
                        $mqtt->ConnectAndPublish($topic, 0);
                        if ($output === true) {
                            return $this->responseSuccess("Seen turn off auto mode successfully");
                        } 
                    }
                }
            }
            // return $this->responseSuccess("Seen turn off auto mode successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/ppmAutoOn",
     *     description="Turn on ppm auto mode",
     *     tags={"Ppm auto"},
     *     summary="Turn on ppm auto mode",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Turn on ppm auto mode",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
     *                  type="integer",
     *              ),
     *              @SWG\property(
     *                  property="nutrientId",
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
    public function ppmAutoOn(Request $request)
    {
        try {
            set_time_limit(0);
            $validator = PpmAutomatic::validate($request->all(), 'Ppm_Auto_On');
            if ($validator) {
                return $this->responseErrorValidator($validator, 422);
            }

            $checkDevices = Devices::where(['id' => $request->devicesId])->first();
            if (!$checkDevices) {
                return $this->responseErrorCustom("devices_id_not_found", 404);
            }
            else {
                $checkNutrients = Nutrients::where(['id' => $request->nutrientId])->first();
                if (!$checkNutrients) {
                    return $this->responseErrorCustom("nutrients_id_not_found", 404);
                }
                else {
                    if($checkDevices->user_id != $request->user->id) {
                        return $this->responseErrorCustom("permission_denied", 403); //Forbidden
                    }
                    else {
                        $checkAuto = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                        if (!$checkAuto) {
                            return $this->responseErrorCustom("devices_id_not_found", 404);
                        }
                        else {
                            $mess = "1=".$request->nutrientId;
                            $mqtt = new Mqtt();
                            $topic = "thuycanhiot@gmail.com/".$request->devicesId."=ppmAuto";
                            $output = $mqtt->ConnectAndPublish($topic, $mess);
                            if ($output === true) {
                                return $this->responseSuccess("Seen turn on auto ppm successfully");
                            } 
                        }
                        // else {
                        //     $checkAuto->nutrient_id = $request->nutrientId;
                        //     $checkAuto->auto_mode = 1;
                        //     $checkAuto->save();

                        //     //Get nutrient by id
                        //     $nutri = Nutrients::getNutrientById($request->nutrientId);
                        //     $ppmMax = $nutri[0]->ppm_max;
                        //     $ppmMin = $nutri[0]->ppm_min;

                        //     $topic = "thuycanhiot@gmail.com/".$request->devicesId."=";
                        //     $mqtt = new Mqtt();
                        //     $getAutoPpm = PpmAutomatic::getAuto($request->devicesId);
                        //     $case = 0;

                        //     while($getAutoPpm[0]->auto_mode == 1){   
                        //         //get good ppm
                        //         $ppmNow = Sensors::getSensorData($request->devicesId);
                        //         $ppmForDevice = PpmAutomatic::ppmCalculation($ppmNow[0]->temperature, $ppmMax, $ppmMin);
                        //         //mix nutrients
                        //         //Nồng độ đúng
                        //         if(abs($ppmForDevice - $ppmNow[0]->PPM) <= 100) {
                        //             //Lượng nước dưới 20%, bơm nước(rất ít) để tránh cháy máy bơm
                        //             if($ppmNow[0]->water <= 20) {
                        //                 sleep(5);
                        //                 $ppmNow = Sensors::getSensorData($request->devicesId);
                        //                 //Kiểm tra lần 2 tránh sai sót của sensor
                        //                 if($ppmNow[0]->water <= 20) {
                        //                     if($case != 1) {
                        //                         if($case == 2 or $case == 0)    {
                        //                             $checkStatus = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                        //                             $checkAuto->auto_status = 1;
                        //                             $checkAuto->save();
    
                        //                         }
                        //                         $topicPpm = $topic."ppm";
                        //                         $messagePpm = 0;
                        //                         $mqtt->ConnectAndPublish($topicPpm, $messagePpm);
                        //                         $topicWaterIn = $topic."waterIn";
                        //                         $messageWaterIn = 1;
                        //                         $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                         $topicWaterOut = $topic."waterOut";
                        //                         $messageWaterOut = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterOut, $messageWaterOut);
                        //                         $topicMix = $topic."mix";
                        //                         $messageMix = 1;
                        //                         $mqtt->ConnectAndPublish($topicMix, $messageMix);
                        //                         sleep(10);
                        //                         $messageWaterIn = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                         $case = 1;
                        //                         sleep(5);
                        //                     }
                        //                     else {
                        //                         sleep(5);
                        //                     }
                        //                 }
                        //                 else {
                        //                     sleep(5);
                        //                 }
                        //             }
                        //             //Lượng nước trên 20%, dừng quá trình thêm nước
                        //             //Lúc này dinh dưỡng phù hợp với cây trồng
                        //             else {
                        //                 if($case != 2) {
                        //                     $topicWaterIn = $topic."waterIn";
                        //                     $messageWaterIn = 0;
                        //                     $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                     $topicPpm = $topic."ppm";
                        //                     $messagePpm = 0;
                        //                     $mqtt->ConnectAndPublish($topicPpm, $messagePpm);
                        //                     $topicWaterOut = $topic."waterOut";
                        //                     $messageWaterOut = 0;
                        //                     $mqtt->ConnectAndPublish($topicWaterOut, $messageWaterOut);
                        //                     $topicMix = $topic."mix";
                        //                     $messageMix = 0;
                        //                     $mqtt->ConnectAndPublish($topicMix, $messageMix);
                        //                     $checkStatus = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                        //                     $checkAuto->auto_status = 0;
                        //                     $checkAuto->save();
                        //                     sleep(30);
                        //                     $case = 2;
                        //                 }
                        //                 else {
                        //                     sleep(30);
                        //                 }
                        //             }
                        //         }
                        //         //Nồng độ thiếu so với chuẩn: bơm lên trên 50 rồi mới pha
                        //         else if($ppmForDevice - $ppmNow[0]->PPM > 100) {
                        //             sleep(5);
                        //             $ppmNow = Sensors::getSensorData($request->devicesId);
                        //             $ppmForDevice = PpmAutomatic::ppmCalculation($ppmNow[0]->temperature, $ppmMax, $ppmMin);
                        //             //Kiểm tra lần 2 tránh sai sót của sensor
                        //             if($ppmForDevice - $ppmNow[0]->PPM > 100) {
                        //                 //Tránh lãng phí
                        //                 if($case == 2 && $ppmNow[0]->PPM >= $ppmMin && $ppmNow[0]->PPM <= $ppmMax) {
                        //                     sleep(30);
                        //                 }
                        //                 else {
                        //                     //Bơm nước lên 50%
                        //                     if($ppmNow[0]->water < 50) {
                        //                         if($case != 3) {
                        //                             if($case == 2 or $case == 0) {
                        //                                 $checkStatus = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                        //                                 $checkAuto->auto_status = 1;
                        //                                 $checkAuto->save();
                        //                                 $topicMix = $topic."mix";
                        //                                 $messageMix = 1;
                        //                                 $mqtt->ConnectAndPublish($topicMix, $messageMix);
                        //                             }

                        //                             $topicPpm = $topic."ppm";
                        //                             $messagePpm = 0;
                        //                             $mqtt->ConnectAndPublish($topicPpm, $messagePpm);
                        //                             $topicWaterIn = $topic."waterIn";
                        //                             $messageWaterIn = 1;
                        //                             $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                             $topicWaterOut = $topic."waterOut";
                        //                             $messageWaterOut = 0;
                        //                             $mqtt->ConnectAndPublish($topicWaterOut, $messageWaterOut);
                        //                             sleep(5);
                        //                             $case = 3;
                        //                         }
                        //                         else {
                        //                             sleep(5);
                        //                         }
                        //                     }
                        //                     //Thêm dinh dưỡng khi nước >= 50%
                        //                     else {
                        //                         if($case == 2 or $case == 0) {
                        //                             $checkStatus = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                        //                             $checkAuto->auto_status = 1;
                        //                             $checkAuto->save();
                        //                             $topicMix = $topic."mix";
                        //                             $messageMix = 1;
                        //                             $mqtt->ConnectAndPublish($topicMix, $messageMix);
                        //                         }

                        //                         $topicWaterIn = $topic."waterIn";
                        //                         $messageWaterIn = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                         $topicPpm = $topic."ppm";
                        //                         $messagePpm = 1;
                        //                         $mqtt->ConnectAndPublish($topicPpm, $messagePpm);
                        //                         $topicWaterOut = $topic."waterOut";
                        //                         $messageWaterOut = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterOut, $messageWaterOut);
                        //                         sleep(20);
                        //                         $case = 4;
                        //                     }
                        //                 }
                        //             }
                        //             else {
                        //                 sleep(5);
                        //             }
                        //         }
                        //         //Nồng độ dư so với chuẩn
                        //         else if($ppmNow[0]->PPM - $ppmForDevice > 100) {
                        //             sleep(30);
                        //             $ppmNow = Sensors::getSensorData($request->devicesId);
                        //             $ppmForDevice = PpmAutomatic::ppmCalculation($ppmNow[0]->temperature, $ppmMax, $ppmMin);
                        //             //Kiểm tra lần 2 tránh sai sót của sensor
                        //             if($ppmNow[0]->PPM - $ppmForDevice > 100) {
                        //                 //Tránh lãng phí
                        //                 if($case == 2 && $ppmNow[0]->PPM >= $ppmMin && $ppmNow[0]->PPM <= $ppmMax) {
                        //                     sleep(30);
                        //                 }
                        //                 else {
                        //                     //Nước bơm lên không quá 90%
                        //                     //10% nước đang lưu thông trong ống cho nên max trong thùng là 80%
                        //                     //Tính toán dự đoán cho việc pha chế dinh dưỡng
                        //                     $ppmDifference = PpmAutomatic::ppmDifferenceCalculation($ppmNow[0]->water, $ppmNow[0]->PPM, $ppmForDevice);
                        //                     //2TH: 1. Phải bơm nước ra, 2. Có thể bơm thêm nước
                        //                     //1. Phải bơm nước ra $ppmDifference > 100
                        //                     if($ppmDifference > 100) {
                        //                         if($case == 2 or $case == 0) {
                        //                             $checkStatus = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                        //                             $checkAuto->auto_status = 1;
                        //                             $checkAuto->save();
                        //                             $topicMix = $topic."mix";
                        //                             $messageMix = 1;
                        //                             $mqtt->ConnectAndPublish($topicMix, $messageMix);
                        //                         }

                        //                         $topicWaterIn = $topic."waterIn";
                        //                         $messageWaterIn = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                         $topicPpm = $topic."ppm";
                        //                         $messagePpm = 0;
                        //                         $mqtt->ConnectAndPublish($topicPpm, $messagePpm);
                        //                         //Get Pump Status
                        //                         $getPumpStatus = Sensors::getPumpStatus($request->devicesId);
                        //                         $topicPump = $topic."pump";
                        //                         $messagePump = 1;
                        //                         $mqtt->ConnectAndPublish($topicPump, $messagePump);
                        //                         $topicWaterOut = $topic."waterOut";
                        //                         $messageWaterOut = 1;
                        //                         $mqtt->ConnectAndPublish($topicWaterOut, $messageWaterOut);
                        //                         //Bơm nước ra trong vòng 10s sau đó kiểm tra lại -> while
                        //                         sleep(10);
                        //                         $messageWaterOut = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterOut, $messageWaterOut);
                        //                         //Return Pump Status
                        //                         if($getPumpStatus[0]->pump == 0) {
                        //                             $messagePump = 0;
                        //                             $mqtt->ConnectAndPublish($topicPump, $messagePump);
                        //                         }
                        //                         //Chờ cho dữ liệu được gửi
                        //                         sleep(5);
                        //                         $case = 5;
                        //                     }
                        //                     //2. Có thể bơm thêm nước $ppmDifference <= 100
                        //                     else {
                        //                         if($case == 2 or $case == 0) {
                        //                             $checkStatus = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                        //                             $checkAuto->auto_status = 1;
                        //                             $checkAuto->save();
                        //                             $topicMix = $topic."mix";
                        //                             $messageMix = 1;
                        //                             $mqtt->ConnectAndPublish($topicMix, $messageMix);
                        //                         }

                        //                         $topicPpm = $topic."ppm";
                        //                         $messagePpm = 0;
                        //                         $mqtt->ConnectAndPublish($topicPpm, $messagePpm);
                        //                         $topicWaterOut = $topic."waterOut";
                        //                         $messageWaterOut = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterOut, $messageWaterOut);
                        //                         $topicWaterIn = $topic."waterIn";
                        //                         $messageWaterIn = 1;
                        //                         $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                         sleep(10);
                        //                         $messageWaterIn = 0;
                        //                         $mqtt->ConnectAndPublish($topicWaterIn, $messageWaterIn);
                        //                         //Chờ dung dịch được trộn
                        //                         sleep(20);
                        //                         $case = 6;
                        //                     }
                        //                 }
                        //             }
                        //             else {
                        //                 sleep(30);
                        //             }
                        //         }
                        //         //Kiểm tra chế độ tự pha dinh dưỡng
                        //         $getAutoPpm = PpmAutomatic::getAuto($request->devicesId);
                        //     }
                        //     $topicWaterIn = $topic."waterIn";
                        //     $topicWaterOut = $topic."waterOut";
                        //     $topicMix = $topic."mix";
                        //     $message = 0;
                        //     $mqtt->ConnectAndPublish($topicWaterIn, $message);
                        //     $mqtt->ConnectAndPublish($topicWaterOut, $message);
                        //     $mqtt->ConnectAndPublish($topicMix, $message);
                        // }
                    }
                }
            }
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/ppmAutoOff",
     *     description="Turn off ppm auto mode",
     *     tags={"Ppm auto"},
     *     summary="Turn off ppm auto mode",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Parameter(
     *          name="body",
     *          description="Turn off ppm auto mode",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\property(
     *                  property="devicesId",
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
    public function ppmAutoOff(Request $request)
    {
        try {
            $validator = PpmAutomatic::validate($request->all(), 'Ppm_Auto_Off');
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
                    $checkAuto = PpmAutomatic::where(['device_id' => $request->devicesId])->first();
                    if (!$checkAuto) {
                        return $this->responseErrorCustom("devices_id_not_found", 404);
                    }
                    else {
                        // $checkAuto->auto_mode = 0;
                        // $checkAuto->auto_status = 0;
                        // $checkAuto->save();
                        $mess = "0=".$checkAuto->nutrient_id;
                        $mqtt = new Mqtt();
                        $topic = "thuycanhiot@gmail.com/".$request->devicesId."=ppmAuto";
                        $output = $mqtt->ConnectAndPublish($topic, $mess);
                        if ($output === true) {
                            return $this->responseSuccess("Seen turn off auto ppm successfully");
                        } 
                    }
                }
            }
            return $this->responseSuccess("Seen turn off auto ppm successfully");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }
}
