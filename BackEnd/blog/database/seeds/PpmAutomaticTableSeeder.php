<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\PpmAutomatic;

class PpmAutomaticTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ppms = [
            [
                'device_id' => 1,
                'nutrient_id' => 1,
                'auto_mode' => 1,
                'auto_status' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 2,
                'nutrient_id' => 3,
                'auto_mode' => 0,
                'auto_status' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 3,
                'nutrient_id' => 5,
                'auto_mode' => 1,
                'auto_status' => 1,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 4,
                'nutrient_id' => 4,
                'auto_mode' => 0,
                'auto_status' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 5,
                'nutrient_id' => 8,
                'auto_mode' => 1,
                'auto_status' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 6,
                'nutrient_id' => 9,
                'auto_mode' => 0,
                'auto_status' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 7,
                'nutrient_id' => 7,
                'auto_mode' => 0,
                'auto_status' => 1,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];
        
        foreach ($ppms as $key => $ppm) {
            PpmAutomatic::create($ppm);
        }
    }
}
