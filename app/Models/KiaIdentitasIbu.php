<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaIdentitasIbu extends Model
{
    use HasFactory;

    protected $table = 'kia_identitas_ibu';
    protected $fillable = [
        'nama',
        'img_url',
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
        'puskesmas_domisili',
        'nomor_register_kohort_ibu',
        'user_id'
    ];

    public function kia_ayah() {
        return $this->hasOne(KiaIdentitasAyah::class, 'kia_ibu_id');
    }

    public function kia_anak() {
        return $this->hasMany(KiaIdentitasAnak::class, 'kia_ibu_id');
    }

    public function immunization() {
        return $this->hasMany(ServiceStatementIbuImmunization::class, 'kia_ibu_id');
    }

    public function init_fundamental_data() {
        $this->init_immunization();
    }

    private function init_immunization() {
        for($i = 1; $i <= 3; $i ++) {
            for($j = 0; $j < 2; $j ++)
                ServiceStatementIbuImmunization::create([
                    'immunization_id' => Immunization::TETANUS,
                    'trisemester' => $i,
                    'kia_ibu_id' => $this->id
                ]);
        }
    }
}
