<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\FamilyHealthTips;
use App\Models\Notification;
use App\Models\TipsDanInfo;
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
        $data['kesehatan_keluarga'] = FamilyHealthTips::all();
        $data['tips_dan_info'] = TipsDanInfo::with('tips_category')->get();

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

    public function getTipsDanInfo() {
        $data = TipsDanInfo::with('tips_category')->get();
        return Constants::successResponseWithNewValue('data', $data);
    }

    public function getTipsDetail($id) {
        return TipsDanInfo::where('id', $id)->with('tips_category')->first();
    }
}
