<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaIdentitasAnak extends Model
{
    use HasFactory;

    protected $table = 'kia_identitas_anak';
    protected $fillable = [
        'nama',
        'anak_ke',
        'no_akte_kelahiran',
        'nik',
        'gol_darah',
        'tempat_lahir',
        'tanggal_lahir',
        'no_jkn',
        'tanggal_berlaku_jkn',
        'no_kohort',
        'no_catatan_medik'
    ];
}
