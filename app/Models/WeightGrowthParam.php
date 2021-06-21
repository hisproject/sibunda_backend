<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightGrowthParam extends Model
{
    use HasFactory;
    protected $primaryKey = 'week';
    protected $table = 'weight_growths';
    protected $fillable = [
        'week',
        'bottom_obesity_threshold',
        'bottom_over_threshold',
        'bottom_normal_threshold'
    ];
}
