<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends BaseModel
{
    protected $table = 'password_reset';
    protected $fillable = [
        'email', 'token', 'expires_at'
    ];

    public static $rules = array(
        'Rule_RequestResetPassword' => [
            'email' => 'required|regex:/^[a-z][a-z0-9_\.]{2,}@[a-z0-9]{2,}(\.[a-z0-9]{2,}){1,2}$/'
        ],
        'Rule_AcceptResetPassword' => [
            'token' => 'required|string',
            'newPassword' => 'required|string|min:6|max:16',
            'confirmNewPassword' => 'required|same:newPassword'
        ],
    );
}
