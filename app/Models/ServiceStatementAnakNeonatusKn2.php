<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementAnakNeonatusKn2 extends Model
{
    use HasFactory;
    protected $table = 'service_statement_anak_neonatus_kn2';
    protected $fillable = [
        'q_menyusu',
        'q_tali_pusat',
        'q_tanda_bahaya',
        'q_identifikasi_kuning',
        'q_imunisasi_hb',
        'date',
        'time',
        'no_batch',
        'tb',
        'bb',
        'lingkar_kepala',
        'q_skrining_hipotiroid_kongenital',
        'masalah',
        'dirujuk_ke',
        'petugas',
        'catatan_penting',
        'monthly_checkup_id'
    ];
}
