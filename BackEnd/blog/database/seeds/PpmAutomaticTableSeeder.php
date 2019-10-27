<?php

use Illuminate\Database\Seeder;
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
                'auto_mode' => 0,
                'auto_status' => 1,
                "created_at"=> "2019-08-28 03:12:33",
                "updated_at"=> "2019-08-28 03:12:33"
            ],
            [
                'device_id' => 2,
                'nutrient_id' => 3,
                'auto_mode' => 0,
                'auto_status' => 1,
                "created_at"=> "2019-08-28 03:12:34",
                "updated_at"=> "2019-08-28 03:12:34"

            ],
            [
                'device_id' => 3,
                'nutrient_id' => 5,
                'auto_mode' => 1,
                'auto_status' => 1,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"

            ],
            [
                'device_id' => 4,
                'nutrient_id' => 4,
                'auto_mode' => 0,
                'auto_status' => 0,
                "created_at"=> "2019-08-28 03:12:36",
                "updated_at"=> "2019-08-28 03:12:36"

            ],
            [
                'device_id' => 5,
                'nutrient_id' => 8,
                'auto_mode' => 1,
                'auto_status' => 1,
                "created_at"=> "2019-08-28 03:12:37",
                "updated_at"=> "2019-08-28 03:12:37"

            ],
            [
                'device_id' => 6,
                'nutrient_id' => 9,
                'auto_mode' => 0,
                'auto_status' => 0,
                "created_at"=> "2019-08-28 03:12:38",
                "updated_at"=> "2019-08-28 03:12:38"

            ],
            [
                'device_id' => 7,
                'nutrient_id' => 7,
                'auto_mode' => 0,
                'auto_status' => 1,
                "created_at"=> "2019-08-28 03:12:39",
                "updated_at"=> "2019-08-28 03:12:39"

            ],
        ];
        
        foreach ($ppms as $key => $ppm) {
            PpmAutomatic::create($ppm);
        }
    }
}
