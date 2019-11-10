<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PumpAutomatic extends BaseModel
{
    protected $table = 'pump_automatic';
    protected $fillable = [
        'device_id', 'time_on', 'time_off', 'auto'
    ];

    public static $rules = array(
        'Pump_Auto_On' => [
            'devicesId' => 'required|integer',
            'timeOn' => 'required|integer',
            'timeOff' => 'required|integer'
        ],
        'Pump_Auto_Off' => [
            'devicesId' => 'required|integer'
        ],
    );

    public static function getAuto($devicesId) {
        return PumpAutomatic::where('device_id', $devicesId)
        ->get('auto');
    }
}
