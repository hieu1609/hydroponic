<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensors extends BaseModel
{
    protected $table = 'sensors';
    protected $fillable = [
        'device_id', 'temperature', 'humidity', 'light', 'EC', 'PPM', 'water', 'pump'
    ];

    public static $rules = array(
        'Get_Sensor_Data' => [
            'devicesId' => 'required|integer'
        ],
        'Get_Sensor_Data_Chart' => [
            'devicesId' => 'required|integer'
        ],
    );

    public static function getSensorData($devicesId) {
        return Sensors::where('device_id', $devicesId)
        ->orderBy('id', 'desc')
        ->limit(1)
        ->get();
    }

    public static function getSensorDataChart($devicesId) {
        return Sensors::where('device_id', $devicesId)
        ->orderBy('id', 'desc')
        ->limit(15)
        ->get();
    }
}
