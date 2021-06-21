<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TfuGrowthParam extends Model
{
    use HasFactory;
    protected $primaryKey = 'week';
    protected $table = 'tfu_growths';
    protected $fillable = [
        'week',
        'bottom_threshold',
        'normal_threshold',
        'top_threshold'
    ];
}
