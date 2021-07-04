<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidFormAns extends Model
{
    use HasFactory;
    protected $table = 'covid_form_ans';
    protected $fillable = [
        'q_id',
        'form_id',
        'ans'
    ];
}
