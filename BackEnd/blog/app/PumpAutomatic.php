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
    );

}
