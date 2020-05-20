<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PpmAutomatic extends BaseModel
{
    protected $table = 'ppm_automatic';
    protected $fillable = [
        'device_id', 'nutrient_id', 'auto_mode', 'auto_status'
    ];

    public static $rules = array(
        'Ppm_Auto_On' => [
            'devicesId' => 'required|integer',
            'nutrientId' => 'required|integer'
        ],
        'Ppm_Auto_Off' => [
            'devicesId' => 'required|integer'
        ],
    );

    public static function getAuto($devicesId) {
        return PpmAutomatic::where('device_id', $devicesId)
        ->get('auto_mode');
    }
}
