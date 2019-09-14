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
    );

    public static function getAllNotifications() {
        return Notification::orderBy('id', 'desc')
        ->get();
    }

    public static function getNotifications($idUser) {
        return Notification::where('user_id_receive', $idUser)
        ->orderBy('id', 'desc')
        ->get();
    }
}
