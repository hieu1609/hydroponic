<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
                'device_id' => 1,
                'time_on' => 10,
                'time_off' => 30,
                'auto' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 2,
                'time_on' => 5,
                'time_off' => 10,
                'auto' => 1,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 3,
                'time_on' => 15,
                'time_off' => 15,
                'auto' => 1,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 4,
                'time_on' => 15,
                'time_off' => 25,
                'auto' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 5,
                'time_on' => 5,
                'time_off' => 10,
                'auto' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 6,
                'time_on' => 8,
                'time_off' => 15,
                'auto' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'device_id' => 7,
                'time_on' => 10,
                'time_off' => 30,
                'auto' => 1,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];
        
        foreach ($pumps as $key => $pump) {
            PumpAutomatic::create($pump);
        }
    }
}
