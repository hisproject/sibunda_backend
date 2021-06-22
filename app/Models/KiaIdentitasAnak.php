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
        'no_catatan_medik',
        'kia_ibu_id',
        'hpl',
        'hpht',
        'is_janin'
    ];

    public function trisemesters() {
        return $this->hasMany(ServiceStatementIbuHamil::class, 'kia_anak_id');
    }

    public function init_fundamental_data() {
        $this->init_trisemester_data();
    }

    private function init_trisemester_data() {
        for($i = 1; $i <= 3; $i ++)
            ServiceStatementIbuHamil::create([
                'trisemester' => $i,
                'kia_anak_id' => $this->id
            ]);
    }
}
