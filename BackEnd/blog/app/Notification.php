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
            'feedbackTitle' => 'required|string',
            'feedbackContent' => 'required|string'
        ],
        'Send_Notification' => [
            'userId' => 'required|integer',
            'notificationTitle' => 'required|string',
            'notificationContent' => 'required|string'
        ],
        'Seen_Notification' => [
            'notificationId' => 'required|integer'
        ],
    );

    public static function getNotifications($idUser) {
        return Notification::where('user_id_receive', $idUser)
        ->get();
    }
}
