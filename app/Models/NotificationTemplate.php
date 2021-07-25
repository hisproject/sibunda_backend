<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;
    protected $table = 'notification_template';
    protected $fillable = [
        'title',
        'desc',
        'img_url',
        'url'
    ];
}
