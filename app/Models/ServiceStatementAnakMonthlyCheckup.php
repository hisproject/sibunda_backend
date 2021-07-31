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

    public function perkembangan_ans() {
        return $this->hasMany(ServiceStatementMonthlyPerkembangan::class, 'monthly_report_id');
    }

    public function neonatus_six_hours() {
        return $this->hasOne(ServiceStatementAnakNeonatusSixHours::class, 'monthly_checkup_id');
    }

    public function neonatus_kn_1() {
        return $this->hasOne(ServiceStatementAnakNeonatusKn1::class, 'monthly_checkup_id');
    }

    public function neonatus_kn_2() {
        return $this->hasOne(ServiceStatementAnakNeonatusKn2::class, 'monthly_checkup_id');
    }

    public function neonatus_kn_3() {
        return $this->hasOne(ServiceStatementAnakNeonatusKn3::class, 'monthly_checkup_id');
    }
}
