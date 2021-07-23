<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipsCategory extends Model
{
    use HasFactory;
    protected $table = 'tips_category';
    protected $fillable = ['name'];
}
