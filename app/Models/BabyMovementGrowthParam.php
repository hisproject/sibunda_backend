<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabyMovementGrowthParam extends Model
{
    use HasFactory;

    protected $primaryKey = 'week';
    protected $table = 'baby_movement_growths';
    protected $fillable = [
        'week',
        'top_threshold',
        'bottom_threshold'
    ];
}
