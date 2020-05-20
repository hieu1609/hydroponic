<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends BaseModel
{
    protected $table = 'notification';
    protected $fillable = [
        'user_id_send', 'user_id_receive', 'title', 'content', 'seen'
    ];

    public static $rules = array(
        'Post_Feedback' => [
            'feedbackTitle' => 'required|string|max:50',
            'feedbackContent' => 'required|string|max:2000'
        ],
        'Send_Notification' => [
            'userId' => 'required|integer',
            'notificationTitle' => 'required|string|max:50',
            'notificationContent' => 'required|string|max:2000'
        ],
        'Send_Notification_All_Users' => [
            'notificationTitle' => 'required|string|max:50',
            'notificationContent' => 'required|string|max:2000'
        ],
        'Seen_Notification' => [
            'notificationId' => 'required|integer'
        ],
        'Edit_Notification' => [
            'notificationId' => 'required|integer',
            'userIdSend' => 'required|integer',
            'userIdReceive' => 'required|integer',
            'notificationTitle' => 'required|string|max:50',
            'notificationContent' => 'required|string|max:2000',
            'seen' => 'required|boolean'
        ],
        'Delete_Notification' => [
            'notificationId' => 'required|integer'
        ],
        'Control_Mix' => [
            'devicesId' => 'required|integer',
            'message' => 'required|string'
        ],
        'Control_Ppm' => [
            'devicesId' => 'required|integer'
        ],
        'Control_Pump' => [
            'devicesId' => 'required|integer',
            'message' => 'required|string'
        ],
        'Control_Water_In' => [
            'devicesId' => 'required|integer',
            'message' => 'required|string'
        ],
        'Check_Water_In' => [
            'devicesId' => 'required|integer',
        ],     
        'Control_Water_Out' => [
            'devicesId' => 'required|integer',
            'message' => 'required|string'
        ],
        'Check_Water_Out' => [
            'devicesId' => 'required|integer',
        ],   
        'Send_Msg_Via_Mqtt' => [
            'devicesId' => 'required|integer',
            'topic' => 'required|string',
            'message' => 'required|string'
        ],
        'Subscribe_To_Topic' => [
            'devicesId' => 'required|integer',
            'topic' => 'required|string'
        ],
        'Get_Notifications_Admin' => [
            'page' => 'required|integer'
        ],
    );

    public static function getNotificationsAdmin($page) {
        $limit = 10;
        $space = ($page - 1) * $limit;
        return Notification::join('users', 'notification.user_id_receive', '=', 'users.id')
        ->orderBy('notification.id', 'desc')
        ->where('notification.user_id_send', 1)
        ->limit($limit)
        ->offset($space)
        ->get(['notification.*', 'users.username', 'users.admin']);
    }

    public static function getFeedbackAdmin($page) {
        $limit = 10;
        $space = ($page - 1) * $limit;
        return Notification::join('users', 'notification.user_id_send', '=', 'users.id')
        ->orderBy('notification.seen', 'asc')
        ->where('notification.user_id_receive', 1)
        ->limit($limit)
        ->offset($space)
        ->get(['notification.*', 'users.username', 'users.admin']);
    }

    public static function getNotifications($idUser) {
        return Notification::where('user_id_receive', $idUser)
        ->orderBy('id', 'desc')
        ->get();
    }

    public static function getNotificationsUserDelete($idUser) {
        return Notification::where('user_id_receive', $idUser)
        ->orWhere('user_id_send', $idUser)
        ->get();
    }
}
