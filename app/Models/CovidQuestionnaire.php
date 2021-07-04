<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidQuestionnaire extends Model
{
    use HasFactory;
    protected $table = 'covid_questionnaire';
    protected $fillable = [
        'question'
    ];
}
