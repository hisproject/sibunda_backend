<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyHealthTips extends Model
{
    use HasFactory;
    protected $table = 'family_health_tips';
    protected $fillable = [
        'img_url',
        'desc',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
