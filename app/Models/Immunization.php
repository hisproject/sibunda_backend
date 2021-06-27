<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immunization extends Model
{
    use HasFactory;

    const TETANUS = 1;

    const TYPE_UNDER_4_MONTHS = 1;
    const TYPE_ABOVE_4_MONTHS = 2;

    protected $table = 'immunization';
    protected $fillable = ['name'];
}
