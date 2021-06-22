<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementIbuHamil extends Model
{
    use HasFactory;
    protected $table = 'service_statement_ibu_hamil';
    protected $fillable = [
        'bb',
        'tb',
        'imt',
        'trisemester',
        'kia_anak_id'
    ];
}