<?php

namespace App\Http\Controllers;

use Salman\Mqtt\MqttClass\Mqtt;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use App\Notification;
use App\Nutrients;

class UserController extends BaseApiController
{
    private $abc;
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
            $topic = $request->devicesId."=".$request->topic;
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
            $topic = $request->devicesId."=".$request->topic;
            $mqtt = new Mqtt();
            $msg = "";
            $mqtt->ConnectAndSubscribe($topic, function($topic, $message){
                if($message != "") {
                    $abc = $topic."=".$message;
                    exit($abc);
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

            if ($request->ppmMax - $request->ppmMin < 50) {
                return $this->responseErrorCustom("ppmMax_must_be_greater_than_ppmMin_50", 403); //Forbidden
            }

            $nutrient = new Nutrients;
            $nutrient->user_id = $request->user->id;
            $nutrient->plant_name = $request->plantName;
            $nutrient->ppm_min = $request->ppmMin;
            $nutrient->ppm_max = $request->ppmMax;
            $nutrient->save();
            return $this->responseSuccess("Post nutrient successfully");

        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/user/pumpAutoOn",
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
            // $validator = Notification::validate($request->all(), 'Send_Msg_Via_Mqtt');
            // if ($validator) {
            //     return $this->responseErrorValidator($validator, 422);
            // }
            $this->abc = 1;
            $topic = $request->devicesId."=pumpAuto";
            $i = 0;
            $message = 1;
            $mqtt = new Mqtt();
            while($this->abc == 1){   
                $output = $mqtt->ConnectAndPublish($topic, $message);
                if($message == 1) {
                    $message = 0;
                    sleep($request->timeOn);
                }
                else {
                    $message = 1;
                    sleep($request->timeOff);
                }
                // $this->pumpAutoOff();
            }

            if ($output === true) {
                return $this->responseSuccess("Seen to mqtt successfully");
            } 
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }
    /**
     * @SWG\Get(
     *     path="/user/pumpAutoOff",
     *     description="Turn off pump auto mode",
     *     tags={"MQTT"},
     *     summary="Turn off pump auto mode",
     *     security={{"jwt":{}}},
     *
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=500, description="Internal Server Error"),
     * )
     */
    public function pumpAutoOff()
    {
        try {
            $this->abc = 0;
            return $this->responseSuccess("ok");
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), 99999, 500);
        }
    }
}
