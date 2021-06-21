<?php

namespace App\Models;

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
