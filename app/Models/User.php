<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'img',
        'fcm_token',
        'user_group_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function kia_ibu(){
        return $this->hasOne(KiaIdentitasIbu::class, 'user_id');
    }

    public function init_notification() {
        $notifications = [
            [
                'is_message' => false,
                'title' => 'Selamat Datang di SiBunda',
                'desc' => 'Satu aplikasi untuk semua tahap kehamilan dan kesehatan bayi bunda, mulai dari usia 0 - 6 tahun',
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/notif_1.png',
                'url' => 'https://google.com',
                'datetime' => Carbon::now(),
            ],
            [
                'is_message' => true,
                'title' => 'Bunda, Pastikan Kehamilan Bunda Sehat Ya',
                'desc' => 'Jangan lupa untuk periksa rutin ya, serta selalu isikan data perkembangan bunda dan calon buah hati di aplikasi ya.',
                'img_url' => 'https://sibunda.amirmb.com/res/img/home/message_1.png',
                'url' => 'https://google.com',
                'datetime' => Carbon::now()
            ],
        ];

        foreach($notifications as $notification) {
            $notification['user_id'] = $this->id;
            Notification::create($notification);
        }
    }

    public function saveImg($img) {

    }

    public function revokeFCM() {
        $this->fcm_token = null;
        return $this->save();
    }

    public function revokeApiToken() {
        $accessTokens = DB::select('select id from oauth_access_tokens where user_id = ' . $this->id);

        foreach($accessTokens as $at) {
            DB::statement('delete from oauth_refresh_tokens where access_token_id = \'' . $at->id . '\'');
            DB::statement('delete from oauth_access_tokens where id = \'' . $at->id . '\'');
        }
    }
}
