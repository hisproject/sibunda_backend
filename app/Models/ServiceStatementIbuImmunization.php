<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementIbuImmunization extends Model
{
    use HasFactory;
    protected $table = 'service_statement_ibu_immunization';
    protected $fillable = [
      'immunization_id',
      'date',
      'location',
      'pic',
      'trisemester',
      'kia_ibu_id'
    ];
}
