<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $templates = [
            [
                'Selamat Datang di SiBunda!',
                'Satu aplikasi untuk semua tahap kehamilan dan kesehatan bayi bunda, mulai dari usia 0 - 6 tahun',
                'https://sibunda.amirmb.com/res/img/home/notif_1.png',
            ],
            [
                'Bunda, Pastikan Kehamilan Bunda Sehat Ya',
                'Jangan lupa untuk periksa rutin ya, serta selalu isikan data perkembangan bunda dan calon buah hati di aplikasi ya.',
                'https://sibunda.amirmb.com/res/img/home/message_1.png'
            ],
            [
                'Pengingat Pemeriksaan Bunda',
                'Bunda, karena usia kehamilan lebih dari 40 minggu dan bayi belum lahir, harap lakukan pemeriksaan tiap 3 hari sekali ya Bun',
                'https://sibunda.amirmb.com/res/img/home/notif_2.png',
            ]
        ];

        foreach($templates as $t) {
            NotificationTemplate::create([
                'title' => $t[0],
                'desc' => $t[1],
                'img_url' => $t[2],
                'url' => 'https://www.google.com'
            ]);
        }
    }
}
