<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementAnakImmunization extends Model
{
    use HasFactory;
    protected $table = 'service_statement_anak_immunization';
    protected $fillable = [
        'immunization_id',
        'date',
        'location',
        'pic',
        'no_batch',
        'kia_anak_id',
        'month_type'
    ];

    public function immunization() {
        return $this->belongsTo(Immunization::class, 'immunization_id');
    }
}
