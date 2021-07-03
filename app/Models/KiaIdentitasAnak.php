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

    public function years() {
        return $this->hasMany(ServiceStatementAnakYear::class, 'kia_anak_id');
    }

    public function init_fundamental_data() {
        $this->init_trisemester_data();
        $this->init_years_data();
        $this->init_immunization();
    }

    // for janin
    private function init_trisemester_data() {
        for($i = 1; $i <= 3; $i ++)
            ServiceStatementIbuHamil::create([
                'trisemester' => $i,
                'kia_anak_id' => $this->id
            ]);
    }

    // for bayi - anak
    private function init_years_data() {
        for($i = 1; $i <= 6; $i ++) {
            ServiceStatementAnakYear::create([
                'year' => $i,
                'kia_anak_id' => $this->id
            ]);
        }
    }

    private function init_immunization() {
        $immunizations = Immunization::select('id')->orderBy('id')->get();

        // UNDER 4 MONTHS

        // from bcg ... polio tetes 4
        for($i = 1; $i < 9; $i ++)
            ServiceStatementAnakImmunization::create([
                'immunization_id' => $immunizations[$i]->id,
                'kia_anak_id' => $this->id,
                'month_type' => Immunization::TYPE_UNDER_4_MONTHS
            ]);

        // Polio Suntik (IPV) 2 x
        for($i = 0; $i < 2; $i ++)
            ServiceStatementAnakImmunization::create([
                'immunization_id' => $immunizations[9]->id,
                'kia_anak_id' => $this->id,
                'month_type' => Immunization::TYPE_UNDER_4_MONTHS
            ]);

        // PCV 1 & 2
        for($i = 10; $i < 12; $i ++)
            ServiceStatementAnakImmunization::create([
                'immunization_id' => $immunizations[$i]->id,
                'kia_anak_id' => $this->id,
                'month_type' => Immunization::TYPE_UNDER_4_MONTHS
            ]);

        // above 4 months
        for($i = 12; $i < count($immunizations); $i ++)
            ServiceStatementAnakImmunization::create([
                'immunization_id' => $immunizations[$i]->id,
                'kia_anak_id' => $this->id,
                'month_type' => Immunization::TYPE_ABOVE_4_MONTHS
            ]);
    }
}
