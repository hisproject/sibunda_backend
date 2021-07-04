<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidForm extends Model
{
    use HasFactory;
    protected $table = 'covid_form';
    protected $fillable = [
        'is_ibu',
        'date',
        'user_id',
        'result_is_normal',
        'result_desc',
        'kia_anak_id'
    ];
}
