<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Nutrients;

class NutrientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nutrients = [
            [
                'user_id' => 1,
                'plant_name' => 'Húng quế',
                'ppm_min' => 500,
                'ppm_max' => 800,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Bắp cải',
                'ppm_min' => 700,
                'ppm_max' => 1200,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Cần tây',
                'ppm_min' => 750,
                'ppm_max' => 1200,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Cải xoong',
                'ppm_min' => 600,
                'ppm_max' => 1200,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Cải xanh',
                'ppm_min' => 600,
                'ppm_max' => 1200,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Tía tô',
                'ppm_min' => 800,
                'ppm_max' => 1000,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Bạc hà',
                'ppm_min' => 500,
                'ppm_max' => 700,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Cải bó xôi',
                'ppm_min' => 900,
                'ppm_max' => 1750,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Húng lủi',
                'ppm_min' => 650,
                'ppm_max' => 850,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Rau muống',
                'ppm_min' => 400,
                'ppm_max' => 850,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'plant_name' => 'Xà lách',
                'ppm_min' => 400,
                'ppm_max' => 750,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($nutrients as $key => $nutrient) {
            Nutrients::create($nutrient);
        }
    }
}
