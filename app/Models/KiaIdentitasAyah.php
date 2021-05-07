<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaIdentitasAyah extends Model
{
    use HasFactory;

    protected $table = 'kia_identitas_ayah';
    protected $fillable = [
        'nama',
        'nik',
        'pembiayaan',
        'no_jkn',
        'faskes_tk1',
        'faskes_rujukan',
        'gol_darah',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'pekerjaan',
        'alamat_rumah',
        'telp',
    ];
}
