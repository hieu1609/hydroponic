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
        'Post_Nutrient' => [
            'plantName' => 'required|string',
            'ppmMin' => 'required|integer',
            'ppmMax' => 'required|integer'
        ],
        'Edit_Nutrient' => [
            'nutrientId' => 'required|integer',
            'userId' => 'required|integer',
            'plantName' => 'required|string',
            'ppmMin' => 'required|integer',
            'ppmMax' => 'required|integer'
        ],
        'Delete_Nutrient' => [
            'nutrientId' => 'required|integer'
        ],
    );

    public static function getNutrients($idUser) {
        return Nutrients::where('user_id', 1)
        ->orWhere('user_id', $idUser)
        ->orderBy('id', 'asc')
        ->get();
    }

    public static function getAllNutrients() {
        return Nutrients::orderBy('id', 'asc')
        ->get();
    }
}
