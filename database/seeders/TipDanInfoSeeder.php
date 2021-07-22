<?php

namespace Database\Seeders;

use App\Models\FamilyHealthTips;
use App\Models\TipsDanInfo;
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
        $kesehatan_keluarga = [
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/kesehatan_keluarga_1.png',
                'desc' => 'Selamat Berat Badan Bunda Normal'
            ],
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/kesehatan_keluarga_2.png',
                'desc' => 'Gerakan Anak Bunda Kurang Nih Bun!'
            ],
        ];
        $tips_dan_info = [
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/tips_1.png',
                'desc' => 'Nih Bun 5 Makanan Rekomendasi untuk Bunda Hamil Trimester 2'
            ],
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/tips_2.png',
                'desc' => 'Perkembangan Janin Usia 9 Minggu Kehamilan, Yuk Bun Ketahui Selengkapnya!'
            ],
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/tips_3.png',
                'desc' => 'Yuk Bun Ketahui Pola Asuh Bayi Baru Lahir Sampai Usia 1,5 Tahun'
            ],
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/tips_4.png',
                'desc' => 'Bagaimana Cara Memberikan ASI ke Bayi Baru Lahir 0-28 Hari (Neonatus)?'
            ],
        ];


        foreach($kesehatan_keluarga as $d)
            FamilyHealthTips::create($d);

        foreach($tips_dan_info as $d)
            TipsDanInfo::create($d);
    }
}
