<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementAnakNeonatusKn3 extends Model
{
    use HasFactory;
    protected $table = 'service_statement_anak_neonatus_kn3';
    protected $fillable = [
        'q_menyusu',
        'q_tali_pusat',
        'q_vit_k1',
        'q_salep',
        'q_imunisasi_hb',
        'q_kuning1',
        'q_kuning2',
        'q_kuning3',
        'q_kuning4',
        'q_kuning5',
        'masalah',
        'dirujuk_ke',
        'petugas',
        'catatan_penting',
        'monthly_checkup_id'
    ];
}
