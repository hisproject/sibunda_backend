<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementMonthlyPerkembangan extends Model
{
    use HasFactory;
    protected $table = 'service_statement_monthly_perkembangan';
    protected $fillable = [
        'monthly_report_id',
        'questionnaire_id',
        'ans'
    ];
}
