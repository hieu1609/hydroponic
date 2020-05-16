<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 2/22/19
 * Time: 1:29 PM
 */

return [
    'host'     => env('mqtt_host','maqiatto.com'),
    'password' => env('mqtt_password','Lancuoi1234@'),
    'username' => env('mqtt_username','thuycanhiot@gmail.com'),
    'certfile' => env('mqtt_cert_file',''),
    'port'     => env('mqtt_port','1883'),
    'debug'    => env('mqtt_debug',false) //Optional Parameter to enable debugging set it to True
];
