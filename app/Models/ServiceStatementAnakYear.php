<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementAnakYear extends Model
{
    use HasFactory;
    protected $table = 'service_statement_anak_years';
    protected $fillable = [
        'year',
        'kia_anak_id'
    ];
}
