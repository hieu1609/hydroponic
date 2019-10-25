<?php

use Illuminate\Database\Seeder;
use App\PumpAutomatic;

class PumpAutomaticTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pumps = [
            [
                'device_id' => 6,
                'time_on' => 10,
                'time_off' => 30,
                'auto' => 1,
                "created_at"=> "2019-08-28 03:12:33",
                "updated_at"=> "2019-08-28 03:12:33"
            ],
            [
                'device_id' => 2,
                'time_on' => 5,
                'time_off' => 10,
                'auto' => 0,
                "created_at"=> "2019-08-28 03:12:34",
                "updated_at"=> "2019-08-28 03:12:34"

            ],
            [
                'device_id' => 3,
                'time_on' => 15,
                'time_off' => 15,
                'auto' => 1,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"

            ],
            [
                'device_id' => 5,
                'time_on' => 20,
                'time_off' => 60,
                'auto' => 0,
                "created_at"=> "2019-08-28 03:12:36",
                "updated_at"=> "2019-08-28 03:12:36"

            ],
        ];
        
        foreach ($pumps as $key => $pump) {
            PumpAutomatic::create($pump);
        }
    }
}
