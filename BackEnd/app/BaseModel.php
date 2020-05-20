<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class BaseModel extends Model
{
    //
    public static function validate($input = array(), $ruleName = '', $messages = array())
    {
        $rules = static::$rules[$ruleName];
        $validator = Validator::make($input, $rules, $messages);
        return self::getErrors($validator);
    }
    public static function validateCustomRule($input = array(), $rules = array(), $messages = array())
    {
        $validator = Validator::make($input, $rules, $messages);
        return self::getErrors($validator);
    }
    private static function getErrors($validator)
    {
        $errors = Lang::get('errorCodeApi');
        $errorList = array();
        foreach ($validator->errors()->all() as $error) {
            $message = $errors['ApiErrorMessages'][$error];
            $code = $errors['ApiErrorCodes'][$error];
            $errorList[] = [
                'errorCode' => $code,
                'errorMessage' => $message
            ];
        }
        return $errorList;
    }
}
