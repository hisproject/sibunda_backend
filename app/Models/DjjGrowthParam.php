<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DjjGrowthParam extends Model
{
    use HasFactory;
    protected $primaryKey = 'week';
    protected $table = 'djj_growths';
    protected $fillable = [
        'week',
        'bottom_threshold',
        'top_threshold'
    ];
}
