<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends BaseModel
{
    protected $table = 'devices';
    protected $fillable = [
        'user_id'
    ];

    public static $rules = array(
        'Add_Devices' => [
            'userId' => 'required|integer'
        ],
        'Edit_Devices' => [
            'devicesId' => 'required|integer',
            'userId' => 'required|integer',
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

    public static function getDeviceIdForUser($userId) {
        return Devices::where('user_id', $userId)
        ->get();
    }

    public static function getIdNewDevice() {
        return Devices::orderBy('id', 'desc')
        ->limit(1)
        ->get('id');
    }

}
