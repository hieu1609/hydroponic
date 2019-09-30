<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'username' => [
            'required' => 'users_name_required',
            'min' => 'users_name_min',
            'max' => 'users_name_max',
            'string' => 'username_string',
        ],
        'email' => [
            'required' => 'users_email_required',
            'required_without' => 'users_email_required_without',
            'unique' => 'users_email_unique',
            'string' => 'users_email_string',
            'regex' => 'users_email_regex',
        ],
        'password' => [
            'required' => 'users_password_required',
            'min' => 'users_password_min',
            'max' => 'users_password_max',
            'incorrect' => 'users_password_incorrect',
            'string' => 'users_password_string'
        ],
        'newPassword' => [
            'required' => 'users_newPassword_required',
            'min' => 'users_newPassword_min',
            'max' => 'users_newPassword_max',
            'string' => 'users_newPassword_string'
        ],
        'confirmNewPassword' => [
            'required' => 'users_confirmNewPassword_required',
            'min' => 'users_confirmNewPassword_min',
            'max' => 'users_confirmNewPassword_max',
            'string' => 'users_confirmNewPasswordn_string',
            'same' => 'users_confirmNewPassword_same',
        ],
        'currentPassword' => [
            'required' => 'users_currentPassword_required',
            'min' => 'users_currentPassword_min',
            'max' => 'users_currentPassword_max',
            'string' => 'users_currentPassword_string'
        ],
        'confirmPassword' => [
            'required' => 'users_confirmPassword_required',
            'min' => 'users_confirmPassword_min',
            'max' => 'users_confirmPassword_max',
            'string' => 'users_confirmPassword_string',
            'same' => 'users_confirmPassword_same',
        ],
        'askImage' => [
            'image' => "ask_image_image",
            'mimes' => 'ask_image_mimes',
            'size' => "ask_image_size",
            'required' => 'ask_image_required',
            'required_without' => 'image_required_without_content',
        ],
        'learnImage' => [
            'image' => "learn_image_image",
            'mimes' => 'learn_image_mimes',
            'size' => "learn_image_size",
            'required' => 'learn_image_required',
            'max' => "learn_image_size",
        ],
        'learnTitle' => [
            'required' => 'learn_title_required',
            'string' => 'learn_title_string',
            'max' => 'learn_title_max'
        ],
        'learnContent' => [
            'max' => 'learn_content_max',
            'required' => 'learn_content_required',
            'string' => 'learn_content_string'
        ],
        'askContent' => [
            'max' => 'ask_content_max',
            'required' => 'ask_content_required',
            'required_without' => 'content_required_without_image',
            'string' => 'ask_content_string'
        ],
        'learnPriority' => [
            'required' => 'learn_priority_required',
            'boolean' => 'learn_priority_boolean',
            'numberic' => 'learn_priority_numberic',
            'between' => 'learn_priority_between',
        ],
        'videoTitle' => [
            'required' => 'video_title_required',
            'max' => 'video_title_max',
            'string' => 'video_title_string',
        ],
        'videoLink' => [
            'required' => 'video_link_required',
            'max' => 'video_link_max',
            'string' => 'video_link_string',
        ],
        'videoImage' => [
            'image' => "learn_image_image",
            'mimes' => 'learn_image_mimes',
            'size' => "learn_image_size",
            'required' => 'learn_image_required',
            'max' => "learn_image_size",
        ],
        'videoPriority' => [
            'required' => 'video_priority_required',
            'boolean' => 'video_priority_boolean'
        ],
        'commentContent' => [
            'max' => 'comment_content_max',
            'required' => 'comment_content_required',
            'string' => 'comment_string'
        ],
        'askId' => [
            'required' => 'askId_required',
            'integer' => 'askId_integer',
            'exists' => 'askId_exists',
        ],
        'learnId' => [
            'required' => 'learnId_required',
            'integer' => 'learnId_integer',
            'exists' => 'learnId_exists',
        ],
        'commentId' => [
            'required' => 'commentId_required',
            'integer' => 'commentId_integer',
            'exists' => 'commentId_exists',
        ],
        'reactionId' => [
            'required' => 'reactionId_required',
            'integer' => 'reactionId_integer',
            'exists' => 'reactionId_exists',
        ],
        'videoId' => [
            'required' => 'videoId_required',
            'integer' => 'videoId_integer',
            'exists' => 'videoId_exists',
        ],
        'admin' => [
            'required' => 'admin_required',
            'boolean' => 'admin_boolean',
        ],
        'offset' => [
            'integer' => 'offset_integer',
        ],
        'limit' => [
            'integer' => 'limit_integer',
        ],
        'sort' => [
            'in' => 'sort_in',
        ],
        'userId' => [
            'required' => 'userId_required',
            'integer' => 'userId_integer',
            'min' => 'userId_min',
        ],
        'avatar' => [
            'required' => 'avatar_required',
            'image' => 'avatar_image',
            'mimes' => 'avatar_mime',
            'max' => 'avatar_max',
        ],
        'active' => [
            'required' => 'active_required',
            'boolean' => 'active_boolean',
        ],
        'fieldSort' => [
            'string' => 'fieldSort_string'
        ],
        'typeSort' => [
            'in' => 'TypeSort_in',
        ],
        'fieldSearch' => [
            'string' => 'fieldSearch_string'
        ],
        'keySearch' => [
            'string' => 'keySearch_string'
        ],
        'feedbackTitle' => [
            'required' => 'feedback_title_required',
            'string' => 'feedback_title_string',
            'max' => 'feedback_title_max'
        ],
        'feedbackContent' => [
            'required' => 'feedback_content_required',
            'string' => 'feedback_content_string',
            'max' => 'feedback_content_max'
        ],
        'notificationTitle' => [
            'required' => 'notification_title_required',
            'string' => 'notification_title_string',
            'max' => 'notification_title_max'
        ],
        'notificationContent' => [
            'required' => 'notification_content_required',
            'string' => 'notification_content_string',
            'max' => 'notification_content_max'
        ],
        'notificationId' => [
            'required' => 'notification_id_required',
            'integer' => 'notification_id_integer'
        ],
        'userIdSend' => [
            'required' => 'user_id_send_required',
            'integer' => 'user_id_send_integer'
        ],
        'userIdReceive' => [
            'required' => 'user_id_receive_required',
            'integer' => 'user_id_receive_integer'
        ],
        'seen' => [
            'required' => 'seen_required',
            'boolean' => 'seen_id_boolean'
        ],
        'devicesId' => [
            'required' => 'devices_id_required',
            'integer' => 'devices_id_integer'
        ],
        'temperature' => [
            'number' => 'temperature_number'
        ],
        'humidity' => [
            'number' => 'humidity_number'
        ],
        'EC' => [
            'number' => 'EC_number'
        ],
        'water' => [
            'number' => 'water_number'
        ],
        'light' => [
            'integer' => 'light_integer'
        ],
        'PPM' => [
            'integer' => 'PPM_integer'
        ],
        'day' => [
            'integer' => 'day_integer'
        ],
        'type' => [
            'string' => 'type_string'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
