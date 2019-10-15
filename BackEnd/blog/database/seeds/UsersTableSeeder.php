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
                "created_at"=> "2019-08-28 03:12:33",
                "updated_at"=> "2019-08-28 03:12:33"
            ],
            [
                "username"=> "Duc",
                "email"=> "duc@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Ha Noi',
                "admin"=> 0,
                "created_at"=> "2019-08-28 03:12:34",
                "updated_at"=> "2019-08-28 03:12:34"

            ],
            [
                "username" => "Hieu",
                "email" => "hieu@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Bien Hoa',
                "admin" => 0,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"
            ],
            [
                "username" => "Quan",
                "email" => "quan@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Hoi An',
                "admin" => 0,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"
            ],
            [
                "username" => "String",
                "email" => "string@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Can Tho',
                "admin" => 0,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"
            ],
            [
                "username" => "String1",
                "email" => "string1@gmail.com",
                'password' => bcrypt('string'),
                'city' => 'Nha Trang',
                "admin" => 0,
                "created_at"=> "2019-08-28 03:12:35",
                "updated_at"=> "2019-08-28 03:12:35"
            ]
        ];
        
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
