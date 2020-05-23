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

    public static function ppmCalculation($temperature, $ppmMax, $ppmMin) {
        $ppmForDevice = 0;
        if($temperature <= 25) {
            $ppmForDevice = $ppmMax;
        }
        else if($temperature >= 45) {
            $ppmForDevice = $ppmMin;
        }
        else {
            $ppmForDevice = $ppmMax - (($temperature - 25)*0.05)*($ppmMax - $ppmMin);
        }
        return $ppmForDevice;
    }

    public static function ppmDifferenceCalculation($water, $ppm, $ppmForDevice) {
        $ppmWater = 50;
        $remainingWater = 90 - $water;
        $checkWaterAfterMix = ($ppmWater*$remainingWater + $ppm*$water)/90;
        $ppmDifference = $checkWaterAfterMix - $ppmForDevice;
        return $ppmDifference;
    }
}
