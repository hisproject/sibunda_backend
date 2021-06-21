<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomPulseGrowthParam extends Model
{
    use HasFactory;
    protected $primaryKey = 'week';
    protected $table = 'mom_pulse_growths';
    protected $fillable = [
        'week',
        'top_threshold'
    ];
}
