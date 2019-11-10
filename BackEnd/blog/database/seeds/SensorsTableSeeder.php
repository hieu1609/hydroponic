<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Sensors;

class SensorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sensors = [
            [
                'device_id' => 1,
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
                'device_id' => 2,
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
                'device_id' => 3,
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
                'device_id' => 4,
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
                'device_id' => 5,
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
                'device_id' => 6,
                'temperature' => 13.98,
                'humidity' => 30.25,
                'light' => 495,
                'EC' => 1.53,
                'PPM' => 751,
                'water' => 56.40,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:38",
                "updated_at"=> "2019-08-28 03:12:38"
            ],
            [
                'device_id' => 7,
                'temperature' => 35.64,
                'humidity' => 21.17,
                'light' => 1329,
                'EC' => 1.78,
                'PPM' => 1036,
                'water' => 78.09,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:39",
                "updated_at"=> "2019-08-28 03:12:39"
            ],
            [
                'device_id' => 6,
                'temperature' => 13.99,
                'humidity' => 30.35,
                'light' => 496,
                'EC' => 1.43,
                'PPM' => 744,
                'water' => 56.40,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:39",
                "updated_at"=> "2019-08-28 03:12:39"
            ],
            [
                'device_id' => 6,
                'temperature' => 14.52,
                'humidity' => 30.89,
                'light' => 494,
                'EC' => 1.41,
                'PPM' => 748,
                'water' => 56.39,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:40",
                "updated_at"=> "2019-08-28 03:12:40"
            ],
            [
                'device_id' => 6,
                'temperature' => 15.11,
                'humidity' => 29.70,
                'light' => 484,
                'EC' => 1.42,
                'PPM' => 753,
                'water' => 56.38,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:41",
                "updated_at"=> "2019-08-28 03:12:41"
            ],
            [
                'device_id' => 6,
                'temperature' => 16.11,
                'humidity' => 23.70,
                'light' => 488,
                'EC' => 1.39,
                'PPM' => 758,
                'water' => 56.40,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:42",
                "updated_at"=> "2019-08-28 03:12:42"
            ],
            [
                'device_id' => 6,
                'temperature' => 16.35,
                'humidity' => 22.78,
                'light' => 492,
                'EC' => 1.43,
                'PPM' => 751,
                'water' => 56.41,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:43",
                "updated_at"=> "2019-08-28 03:12:43"
            ],
            [
                'device_id' => 6,
                'temperature' => 16.10,
                'humidity' => 25.10,
                'light' => 485,
                'EC' => 1.38,
                'PPM' => 751,
                'water' => 56.37,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:44",
                "updated_at"=> "2019-08-28 03:12:44"
            ],
            [
                'device_id' => 6,
                'temperature' => 16.12,
                'humidity' => 24.75,
                'light' => 481,
                'EC' => 1.37,
                'PPM' => 740,
                'water' => 56.32,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:45",
                "updated_at"=> "2019-08-28 03:12:45"
            ],
            [
                'device_id' => 6,
                'temperature' => 17.20,
                'humidity' => 22.51,
                'light' => 480,
                'EC' => 1.38,
                'PPM' => 745,
                'water' => 56.33,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:46",
                "updated_at"=> "2019-08-28 03:12:46"
            ],
            [
                'device_id' => 6,
                'temperature' => 18.35,
                'humidity' => 21.50,
                'light' => 474,
                'EC' => 1.42,
                'PPM' => 756,
                'water' => 56.32,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:47",
                "updated_at"=> "2019-08-28 03:12:47"
            ],
            [
                'device_id' => 6,
                'temperature' => 20.21,
                'humidity' => 31.50,
                'light' => 412,
                'EC' => 1.29,
                'PPM' => 721,
                'water' => 56.80,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:48",
                "updated_at"=> "2019-08-28 03:12:48"
            ],
            [
                'device_id' => 6,
                'temperature' => 21.29,
                'humidity' => 31.78,
                'light' => 488,
                'EC' => 1.41,
                'PPM' => 748,
                'water' => 56.36,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:49",
                "updated_at"=> "2019-08-28 03:12:49"
            ],
            [
                'device_id' => 6,
                'temperature' => 22.17,
                'humidity' => 35.71,
                'light' => 496,
                'EC' => 1.41,
                'PPM' => 758,
                'water' => 56.14,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:50",
                "updated_at"=> "2019-08-28 03:12:50"
            ],
            [
                'device_id' => 6,
                'temperature' => 23.41,
                'humidity' => 35.05,
                'light' => 498,
                'EC' => 1.40,
                'PPM' => 746,
                'water' => 56.23,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:51",
                "updated_at"=> "2019-08-28 03:12:51"
            ],
            [
                'device_id' => 6,
                'temperature' => 23.79,
                'humidity' => 35.15,
                'light' => 510,
                'EC' => 1.41,
                'PPM' => 752,
                'water' => 56.21,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:52",
                "updated_at"=> "2019-08-28 03:12:52"
            ],
            [
                'device_id' => 6,
                'temperature' => 22.15,
                'humidity' => 36.25,
                'light' => 512,
                'EC' => 1.40,
                'PPM' => 750,
                'water' => 56.22,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:53",
                "updated_at"=> "2019-08-28 03:12:53"
            ],
            [
                'device_id' => 6,
                'temperature' => 24.29,
                'humidity' => 37.25,
                'light' => 518,
                'EC' => 1.38,
                'PPM' => 742,
                'water' => 56.20,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:54",
                "updated_at"=> "2019-08-28 03:12:54"
            ],
            [
                'device_id' => 6,
                'temperature' => 25.41,
                'humidity' => 40.05,
                'light' => 540,
                'EC' => 1.38,
                'PPM' => 751,
                'water' => 56.22,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:55",
                "updated_at"=> "2019-08-28 03:12:55"
            ],
            [
                'device_id' => 6,
                'temperature' => 26.11,
                'humidity' => 42.35,
                'light' => 558,
                'EC' => 1.39,
                'PPM' => 753,
                'water' => 56.20,
                'pump' => 0,
                "created_at"=> "2019-08-28 03:12:56",
                "updated_at"=> "2019-08-28 03:12:56"
            ],
        ];
        
        foreach ($sensors as $key => $sensor) {
            Sensors::create($sensor);
        }
    }
}
