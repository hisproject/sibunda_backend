<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementAnakNeonatusSixHours extends Model
{
    use HasFactory;
    protected $table = 'service_statement_anak_neonatus_six_hours';
    protected $fillable = [
        'bb',
        'tb',
        'lingkar_kepala',
        'q_imd',
        'q_vit_k1',
        'q_salep',
        'q_imunisasi_hb',
        'date',
        'time',
        'no_batch',
        'dirujuk_ke',
        'petugas',
        'catatan_penting',
        'monthly_checkup_id'
    ];
}
