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
                "created_at"=> "2019-08-28 03:12:33",
                "updated_at"=> "2019-08-28 03:12:33"
            ],
            [
                'user_id' => 2,
                "created_at"=> "2019-08-28 03:12:34",
                "updated_at"=> "2019-08-28 03:12:34"
            ],
            [
                'user_id' => 3,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"
            ],
            [
                'user_id' => 4,
                "created_at"=> "2019-08-28 03:12:36",
                "updated_at"=> "2019-08-28 03:12:36"
            ],
            [
                'user_id' => 4,
                "created_at"=> "2019-08-28 03:12:37",
                "updated_at"=> "2019-08-28 03:12:37"
            ],
            [
                'user_id' => 5,
                "created_at"=> "2019-08-28 03:12:38",
                "updated_at"=> "2019-08-28 03:12:38"
            ],
            [
                'user_id' => 6,
                "created_at"=> "2019-08-28 03:12:39",
                "updated_at"=> "2019-08-28 03:12:39"
            ]
        ];
        
        foreach ($devices as $key => $device) {
            Devices::create($device);
        }
    }
}
