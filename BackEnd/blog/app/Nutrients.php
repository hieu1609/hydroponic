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
        'Get_Nutrients_Admin' => [
            'page' => 'required|integer'
        ],
        'Add_Nutrient' => [
            'plantName' => 'required|string',
            'ppmMin' => 'required|integer',
            'ppmMax' => 'required|integer'
        ],
    );

    public static function getNutrientsAdmin($page) {
        $limit = 10;
        $space = ($page - 1) * $limit;
        return Nutrients::join('users', 'nutrients.user_id', '=', 'users.id')
        ->orderBy('nutrients.id', 'asc')
        ->limit($limit)
        ->offset($space)
        ->get(['nutrients.*', 'users.username', 'users.admin']);
    }


    public static function getNutrients($idUser) {
        return Nutrients::where('user_id', 1)
        ->orWhere('user_id', $idUser)
        ->orderBy('id', 'asc')
        ->get();
    }

    public static function getNutrientById($id) {
        return Nutrients::where('id', $id)
        ->get();
    }

    public static function getNutrientsUserDelete($idUser) {
        return Nutrients::where('user_id', $idUser)
        ->get();
    }
}
