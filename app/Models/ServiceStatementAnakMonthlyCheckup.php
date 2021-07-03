<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementAnakMonthlyCheckup extends Model
{
    use HasFactory;
    protected $table = 'service_statement_anak_monthly_checkup';
    protected $fillable = [
        'year_id',
        'month',
        'date',
        'location',
        'age',
        'bb',
        'tb',
        'lingkar_kepala',
        'imt',
    ];

    public function year() {
        return $this->belongsTo(ServiceStatementAnakYear::class, 'year_id');
    }
}
