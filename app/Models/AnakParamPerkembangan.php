<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnakParamPerkembangan extends Model
{
    use HasFactory;
    protected $table = 'anak_param_perkembangan';
    protected $fillable = [
        'is_laki',
        'month',
        's_threshold',
        'm_threshold'
    ];
}
