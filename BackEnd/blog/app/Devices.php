<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends BaseModel
{
    protected $table = 'devices';
    protected $fillable = [
        'user_id', 'temperature', 'humidity', 'light', 'EC', 'PPM', 'water', 'pump', 'type', 'day'
    ];

    public static $rules = array(
        'Add_Devices' => [
            'userId' => 'required|integer'
        ],
        'Edit_Devices' => [
            'devicesId' => 'required|integer',
            'userId' => 'required|integer',
            'light' => 'integer',
            'PPM' => 'integer',
            'pump' => 'boolean',
            'type' => 'string',
            'day' => 'integer',
        ],
        'Delete_Devices' => [
            'devicesId' => 'required|integer'
        ],
        'Pump_Devices' => [
            'devicesId' => 'required|integer',
            'pump' => 'boolean',
        ],
    );

    public static function getAllDevices() {
        return Devices::orderBy('id', 'asc')
        ->get();
    }

    public static function getData($idUser) {
        return Devices::where('user_id', $idUser)
        ->get();
    }
}
