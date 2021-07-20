<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Utils\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function getHomeData() {
        $data['header'] = [
            'img_url' => 'https://cdn.popbela.com/content-images/post/20180626/18095091-1852239135027406-6035530276199727104-n-b2395d94b5924b9c4f5e6a866acf7c99_750x500.jpg',
            'name' => 'Andini Putri',
            'age' => '27 Tahun'
        ];
        $data['kesehatan_keluarga'] = [
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/kesehatan_keluarga_1.png',
                'desc' => 'Selamat Berat Badan Bunda Normal'
            ],
            [
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/kesehatan_keluarga_2.png',
                'desc' => 'Gerakan Anak Bunda Kurang Nih Bun!'
            ],
        ];
        $data['tips_dan_info'] = [
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

        return Constants::successResponseWithNewValue('data', $data);
    }

    public function getNotifications() {
        $data = Notification::where(function($q) {
                                    $q->where('user_id', Auth::id())
                                        ->orWhere('user_id', null);
                                })->where('is_message', false)
                                ->orderBy('created_at', 'desc')->get();

        return Constants::successResponseWithNewValue('data', $data);
    }

    public function getMessages() {
        $data = Notification::where(function($q) {
            $q->where('user_id', Auth::id())
                ->orWhere('user_id', null);
        })->where('is_message', true)
            ->orderBy('created_at', 'desc')->get();

        return Constants::successResponseWithNewValue('data', $data);
    }
}
