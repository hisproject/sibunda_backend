<?php

namespace Database\Seeders;

use App\Models\FamilyHealthTips;
use App\Models\TipsCategory;
use App\Models\TipsDanInfo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TipDanInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $host = getenv('APP_URL');
        $tips_categories = ['Kehamilan'];
        $kesehatan_keluarga = [
            [
                'img_url' => $host . '/res/img/home/kesehatan_keluarga_1.png',
                'desc' => 'Selamat Berat Badan Bunda Normal'
            ],
            [
                'img_url' => $host . '/res/img/home/kesehatan_keluarga_2.png',
                'desc' => 'Gerakan Anak Bunda Kurang Nih Bun!'
            ],
        ];
        $tips_dan_info = [
            [
                'img_url' => $host . '/res/img/home/tips_1.png',
                'desc' => 'Nih Bun 5 Makanan Rekomendasi untuk Bunda Hamil Trimester 2',
                'date' => Carbon::now(),
                'content' => 'Setiap trimester dalam kehamilan adalah fase yang penting bagi tumbuh kembang calon buah hati. Memasuki trimester kedua, ibu hamil akan merasa lebih nyaman dibanding trimester sebelumnya, karena rasa mual akibat morning sickness sudah berkurang. Oleh karena itu, saatnya bagi ibu hamil untuk memaksimalkan konsumsi makanan yang disarankan.
                              Ada banyak pilihan makanan untuk tumbuh kembang janin yang bisa dikonsumsi ibu hamil trimester kedua. Ibu hamil disarankan untuk mengonsumsi makanan yang kaya nutrisi, seperti zat besi, folat, protein, kalsium, magnesium, dan vitamin D.
                                Dengan mencukupi berbagai nutrisi tersebut, ibu hamil dapat menurunkan risiko berbagai masalah pada kehamilan, seperti preeklampsia dan risiko kelahiran prematur',
                'tips_category_id' => 1
            ],
            [
                'img_url' => $host . '/res/img/home/tips_2.png',
                'desc' => 'Perkembangan Janin Usia 9 Minggu Kehamilan, Yuk Bun Ketahui Selengkapnya!',
                'date' => Carbon::now(),
                'content' => 'Setiap trimester dalam kehamilan adalah fase yang penting bagi tumbuh kembang calon buah hati. Memasuki trimester kedua, ibu hamil akan merasa lebih nyaman dibanding trimester sebelumnya, karena rasa mual akibat morning sickness sudah berkurang. Oleh karena itu, saatnya bagi ibu hamil untuk memaksimalkan konsumsi makanan yang disarankan.
                              Ada banyak pilihan makanan untuk tumbuh kembang janin yang bisa dikonsumsi ibu hamil trimester kedua. Ibu hamil disarankan untuk mengonsumsi makanan yang kaya nutrisi, seperti zat besi, folat, protein, kalsium, magnesium, dan vitamin D.
                                Dengan mencukupi berbagai nutrisi tersebut, ibu hamil dapat menurunkan risiko berbagai masalah pada kehamilan, seperti preeklampsia dan risiko kelahiran prematur',
                'tips_category_id' => 1
            ],
            [
                'img_url' => $host . '/res/img/home/tips_3.png',
                'desc' => 'Yuk Bun Ketahui Pola Asuh Bayi Baru Lahir Sampai Usia 1,5 Tahun',
                'date' => Carbon::now(),
                'content' => 'Setiap trimester dalam kehamilan adalah fase yang penting bagi tumbuh kembang calon buah hati. Memasuki trimester kedua, ibu hamil akan merasa lebih nyaman dibanding trimester sebelumnya, karena rasa mual akibat morning sickness sudah berkurang. Oleh karena itu, saatnya bagi ibu hamil untuk memaksimalkan konsumsi makanan yang disarankan.
                              Ada banyak pilihan makanan untuk tumbuh kembang janin yang bisa dikonsumsi ibu hamil trimester kedua. Ibu hamil disarankan untuk mengonsumsi makanan yang kaya nutrisi, seperti zat besi, folat, protein, kalsium, magnesium, dan vitamin D.
                                Dengan mencukupi berbagai nutrisi tersebut, ibu hamil dapat menurunkan risiko berbagai masalah pada kehamilan, seperti preeklampsia dan risiko kelahiran prematur',
                'tips_category_id' => 1
            ],
            [
                'img_url' => $host . '/res/img/home/tips_4.png',
                'desc' => 'Bagaimana Cara Memberikan ASI ke Bayi Baru Lahir 0-28 Hari (Neonatus)?',
                'date' => Carbon::now(),
                'content' => 'Setiap trimester dalam kehamilan adalah fase yang penting bagi tumbuh kembang calon buah hati. Memasuki trimester kedua, ibu hamil akan merasa lebih nyaman dibanding trimester sebelumnya, karena rasa mual akibat morning sickness sudah berkurang. Oleh karena itu, saatnya bagi ibu hamil untuk memaksimalkan konsumsi makanan yang disarankan.
                              Ada banyak pilihan makanan untuk tumbuh kembang janin yang bisa dikonsumsi ibu hamil trimester kedua. Ibu hamil disarankan untuk mengonsumsi makanan yang kaya nutrisi, seperti zat besi, folat, protein, kalsium, magnesium, dan vitamin D.
                                Dengan mencukupi berbagai nutrisi tersebut, ibu hamil dapat menurunkan risiko berbagai masalah pada kehamilan, seperti preeklampsia dan risiko kelahiran prematur',
                'tips_category_id' => 1
            ],
        ];

        foreach ($tips_categories as $d)
            TipsCategory::create([
                'name' => $d
            ]);

        foreach($kesehatan_keluarga as $d)
            FamilyHealthTips::create($d);

        foreach($tips_dan_info as $d)
            TipsDanInfo::create($d);
    }
}
