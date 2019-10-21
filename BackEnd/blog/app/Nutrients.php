<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nutrients extends BaseModel
{
    protected $table = 'nutrients';
    protected $fillable = [
        'user_id', 'plant_name', 'ppm_min', 'ppm_max'
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
}
