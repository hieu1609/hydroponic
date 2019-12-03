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
        'Get_Devices_Admin' => [
            'page' => 'required|integer'
        ],
    );

    public static function getDevicesAdmin($page) {
        $limit = 10;
        $space = ($page - 1) * $limit;
        return Devices::orderBy('id', 'asc')
        ->limit($limit)
        ->offset($space)
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
