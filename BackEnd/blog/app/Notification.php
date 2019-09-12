<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends BaseModel
{
    protected $table = 'notification';
    protected $fillable = [
        'user_id_send', 'user_id_receive', 'title', 'content', 'seen'
    ];

    // public static $rules = array(
    //     'Rule_RequestResetPassword' => [
    //         'email' => 'required|regex:/^[a-z][a-z0-9_\.]{2,}@[a-z0-9]{2,}(\.[a-z0-9]{2,}){1,2}$/'
    //     ],
    //     'Rule_AcceptResetPassword' => [
    //         'token' => 'required|string',
    //         'newPassword' => 'required|string|min:6|max:16',
    //         'confirmNewPassword' => 'required|same:newPassword'
    //     ],
    // );
}
