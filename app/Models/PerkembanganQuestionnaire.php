<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkembanganQuestionnaire extends Model
{
    use HasFactory;
    protected $table = 'perkembangan_questionnaire';
    protected $fillable = [
        'question',
        'img_url',
        'month_start',
        'month_until'
    ];
}
