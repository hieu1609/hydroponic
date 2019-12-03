<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route Authentication
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('request/reset-password', 'AuthController@requestResetPassword');
    Route::post('accept/reset-password', 'AuthController@acceptResetPassword');
    Route::group(['middleware' => ['jwt']], function () {
        Route::put('change-password', 'AuthController@changePassword');
        Route::post('logout', 'AuthController@logout');
    });
});

//Route Devices
Route::group(['prefix' => 'devices'], function () {
    Route::group(['middleware' => ['jwt']], function () {
        Route::get('getDeviceIdForUser', 'DevicesController@getDeviceIdForUser');
        Route::post('getSensorData', 'DevicesController@getSensorData');
        Route::post('getSensorDataChart', 'DevicesController@getSensorDataChart');
    });
});

//Route User
Route::group(['prefix' => 'user'], function () {
    Route::group(['middleware' => ['jwt']], function () {
        Route::get('getNotifications', 'UserController@getNotifications');
        Route::post('postFeedback', 'UserController@postFeedback');
        Route::put('seenNotification', 'UserController@seenNotification');

        //MQTT
        Route::post('controlPump', 'UserController@controlPump');
        Route::post('sendMsgViaMqtt', 'UserController@sendMsgViaMqtt');
        Route::post('subscribetoTopic', 'UserController@subscribetoTopic');

        //Pump automatic
        Route::post('pumpAutoOn', 'UserController@pumpAutoOn');
        Route::post('pumpAutoOff', 'UserController@pumpAutoOff');

        //Nutrients
        Route::get('getNutrients', 'UserController@getNutrients');
        Route::post('postNutrient', 'UserController@postNutrient');

        //Ppm automatic
        Route::post('ppmAutoOn', 'UserController@ppmAutoOn');
        Route::post('ppmAutoOff', 'UserController@ppmAutoOff');
    });
});

//Route Weather
Route::group(['middleware' => ['jwt']], function () {
    Route::post('weather/currentweather', 'LaravelOWMController@currentweather');
    Route::post('weather/forecast', 'LaravelOWMController@forecast');        
});

//Route Admin
Route::group(['prefix' => 'admin'], function () {
    Route::middleware(['jwt', 'admin'])->group(function () {
        //User
        Route::post('getUserAdmin', 'AdminController@getUserAdmin');
        Route::get('all-user', 'AdminController@getAllUser');
        Route::post('addUser', 'AdminController@addUser');
        Route::put('/{id}', 'AdminController@editUser');
        Route::delete('/{id}', 'AdminController@deleteUser');

        //Notification
        Route::post('getNotificationsAdmin', 'AdminController@getNotificationsAdmin');
        Route::post('sendNotification', 'AdminController@sendNotification');
        Route::post('sendNotificationForAllUsers', 'AdminController@sendNotificationForAllUsers');
        Route::put('notification/{notificationId}', 'AdminController@editNotification');
        Route::delete('notification/{notificationId}', 'AdminController@deleteNotification');

        //Devices
        Route::post('getDevicesAdmin', 'AdminController@getDevicesAdmin');
        Route::post('addDevice', 'AdminController@addDevice');
        Route::put('devices/{devicesId}', 'AdminController@editDevices');
        Route::delete('devices/{devicesId}', 'AdminController@deleteDevices');

        //Nutrients
        Route::post('getNutrientsAdmin', 'AdminController@getNutrientsAdmin');
        Route::post('addNutrient', 'AdminController@addNutrient');
        Route::put('nutrient/{nutrientId}', 'AdminController@editNutrient');
        Route::delete('nutrient/{nutrientId}', 'AdminController@deleteNutrient');
    });
});