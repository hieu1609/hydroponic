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

    );
}
