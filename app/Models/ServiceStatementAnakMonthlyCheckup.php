<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementAnakMonthlyCheckup extends Model
{
    use HasFactory;
    protected $table = 'service_statement_anak_monthly_checkup';
    protected $fillable = [
        'year_id',
        'date',
        'location',
        'age',
        'bb',
        'tb',
        'lingkar_kepala',
        'imt',
        'perkembangan_q1',
        'perkembangan_q2',
        'perkembangan_q3',
        'perkembangan_q4',
        'perkembangan_q5',
        'perkembangan_q6',
        'perkembangan_q7',
        'perkembangan_q8',
        'perkembangan_q9',
        'perkembangan_q10',
    ];

    public function fill_perkembangan_qs($data) {
        foreach($data as $key => $d) {
            $field = 'perkembangan_q' . $key;
            $this->$field = $d; // must be either 1 or 0
        }

        $this->save();
    }


}
