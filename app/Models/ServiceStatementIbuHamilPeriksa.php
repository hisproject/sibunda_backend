<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementIbuHamilPeriksa extends Model
{
    use HasFactory;
    protected $table = 'service_statement_ibu_hamil_periksa';
    protected $fillable = [
        'week',
        'tanggal_periksa',
        'tempat_periksa',
        'nama_pemeriksa',
        'keluhan_bunda',
        'jenis_kelamin',
        'tanggal_periksa_kembali',
        'hpl',
        'bb',
        'kenaikan_bb',
        'tb',
        'tfu',
        'djj',
        'sistolik',
        'diastolik',
        'map',
        'gerakan_bayi',
        'resep_obat',
        'alergi_obat',
        'riwayat_penyakit',
        'catatan_khusus',
        'trisemester_id'
    ];
}
