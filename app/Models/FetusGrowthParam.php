<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FetusGrowthParam extends Model
{
    use HasFactory;
    protected $primaryKey = 'week';
    protected $table = 'fetus_growths';
    protected $fillable = [
        'week',
        'length',
        'weight',
        'desc',
        'img'
    ];
}
