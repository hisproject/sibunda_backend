<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Notification::query()->truncate();
        DB::statement('ALTER SEQUENCE notifications_id_seq RESTART 1');

        $notifications = [
            [
                'is_message' => false,
                'title' => 'Selamat Datang di SiBunda',
                'desc' => 'Satu aplikasi untuk semua tahap kehamilan dan kesehatan bayi bunda, mulai dari usia 0 - 6 tahun',
                'img_url' => null,
                'url' => 'https://google.com',
                'user_id' => 5
            ],
            [
                'is_message' => true,
                'title' => 'Bunda, Pastikan Kehamilan Bunda Sehat Ya',
                'desc' => 'Jangan lupa untuk periksa rutin ya, serta selalu isikan data perkembangan bunda dan calon buah hati di aplikasi ya.',
                'img_url' => null,
                'url' => 'https://google.com',
                'user_id' => 5
            ],
        ];

        foreach($notifications as $notification)
            Notification::create($notification);
    }
}
