<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kia extends Model
{
    use HasFactory;

    protected $table = 'kia';
    protected $fillable = [
        'kia_anak_id',
        'kia_ibu_id',
        'kia_ayah_id'
    ];
}
