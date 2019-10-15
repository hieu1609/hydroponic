<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 2/22/19
 * Time: 1:29 PM
 */

return [
    'host'     => env('mqtt_host','m24.cloudmqtt.com'),
    'password' => env('mqtt_password','7fub13-eRIeR'),
    'username' => env('mqtt_username','tmlgemnz'),
    'certfile' => env('mqtt_cert_file',''),
    'port'     => env('mqtt_port','15217'),
    'debug'    => env('mqtt_debug',false) //Optional Parameter to enable debugging set it to True
];
