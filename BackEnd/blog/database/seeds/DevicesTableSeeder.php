<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Devices;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $devices = [
            [
                'user_id' => 2,
                'temperature' => 28.47,
                'humidity' => 56.61,
                'light' => 532,
                'EC' => 1.62,
                'PPM' => 465,
                'water' => 56.45,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:33",
                "updated_at"=> "2019-08-28 03:12:33"
            ],
            [
                'user_id' => 2,
                'temperature' => 27.85,
                'humidity' => 59.02,
                'light' => 560,
                'EC' => 1.75,
                'PPM' => 501,
                'water' => 58.95,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:34",
                "updated_at"=> "2019-08-28 03:12:34"
            ],
            [
                'user_id' => 3,
                'temperature' => 33.76,
                'humidity' => 86.43,
                'light' => 125,
                'EC' => 0.94,
                'PPM' => 1632,
                'water' => 78.57,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"
            ],
            [
                'user_id' => 4,
                'temperature' => 23.65,
                'humidity' => 66.63,
                'light' => 362,
                'EC' => 0.84,
                'PPM' => 763,
                'water' => 86.08,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:36",
                "updated_at"=> "2019-08-28 03:12:36"
            ],
            [
                'user_id' => 4,
                'temperature' => 28.47,
                'humidity' => 56.61,
                'light' => 532,
                'EC' => 1.62,
                'PPM' => 465,
                'water' => 56.45,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:37",
                "updated_at"=> "2019-08-28 03:12:37"
            ],
            [
                'user_id' => 5,
                'temperature' => 13.98,
                'humidity' => 30.25,
                'light' => 495,
                'EC' => 1.53,
                'PPM' => 751,
                'water' => 26.40,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:38",
                "updated_at"=> "2019-08-28 03:12:38"
            ],
            [
                'user_id' => 6,
                'temperature' => 35.64,
                'humidity' => 21.17,
                'light' => 1329,
                'EC' => 1.78,
                'PPM' => 1036,
                'water' => 78.09,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:39",
                "updated_at"=> "2019-08-28 03:12:39"
            ]
        ];
        
        foreach ($devices as $key => $device) {
            Devices::create($device);
        }
    }
}
