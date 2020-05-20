<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Notification;

class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = [
            [
                'user_id_send' => 2,
                'user_id_receive' => 1,
                'title' => 'The problem',
                'content' => 'I have a problem',
                'seen' => 1,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id_send' => 1,
                'user_id_receive' => 2,
                'title' => 'Reply problem',
                'content' => 'OK I will fix it',
                'seen' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'user_id_send' => 3,
                'user_id_receive' => 1,
                'title' => 'Hydroponic',
                'content' => 'your service very good',
                'seen' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id_send' => 1,
                'user_id_receive' => 3,
                'title' => 'Hydroponic',
                'content' => 'Thank you',
                'seen' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id_send' => 4,
                'user_id_receive' => 1,
                'title' => 'How to use it',
                'content' => 'I want a timer at 7am',
                'seen' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'user_id_send' => 2,
                'user_id_receive' => 1,
                'title' => 'Reply: How to use it',
                'content' => 'Once received, tap the link sent to you in the text message to open the Google Play Store on your device.',
                'seen' => 0,
                "created_at"=> Carbon::now()->format('Y-m-d H:i:s'),
                "updated_at"=> Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($notifications as $key => $notification) {
            Notification::create($notification);
        }
    }
}
