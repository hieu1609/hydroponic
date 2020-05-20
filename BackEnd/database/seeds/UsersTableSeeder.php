<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username' => 'Admin',
                'email' => 'admin' . '@gmail.com',
                'password' => bcrypt('string'),
                'city' => 'Ho Chi Minh City',
                'admin' => 1,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                "username"=> "Duc",
                "email"=> "duc@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Ha Noi',
                "admin"=> 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                "username" => "Hieu",
                "email" => "hieu@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Bien Hoa',
                "admin" => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                "username" => "Quan",
                "email" => "quan@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Hoi An',
                "admin" => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                "username" => "String",
                "email" => "string@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Can Tho',
                "admin" => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                "username" => "String1",
                "email" => "string1@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Nha Trang',
                "admin" => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ], 
            [
                "username" => "giahieua2",
                "email" => "giahieua2ltv@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Vung Tau',
                "admin" => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
