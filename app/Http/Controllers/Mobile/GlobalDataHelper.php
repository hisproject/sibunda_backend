<?php


namespace App\Http\Controllers\Mobile;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GlobalDataHelper
{
    // return description string
    public function getChildAge($bornDateStr) {
        try {
            $nowDate = Carbon::now();
            $bornDate = Carbon::parse($bornDateStr);
            $days = $bornDate->diffInDays($nowDate);

            if($days > 30) {
                if(($days % 30) > 7) {
                    return ($days / 30) . ' Bulan lebih ' . (($days % 30) / 7) . ' Minggu ' .
                        (($days % 30) % 7) . ' Hari';
                } else
                    return ($days / 30) . ' Bulan lebih ' . ($days % 30) . ' Hari';
            } else if($days > 7) {
                return ($days / 7) . ' Minggu lebih ' . ($days % 7) . ' Hari';
            } else
                return $days  . ' Hari';
        } catch (\Exception $e) {}

        return null;
    }

    public function getPregnancyAgeInWeek($hpl) {
        $nowDate = Carbon::now();
        $hplDate = Carbon::parse($hpl);
        $week = 40 - $hplDate->diffInWeeks($nowDate);
        if($week < 1)
            $week = 1;
        return $week;
    }

    public function getRemainingPregnancyDays($hpl) {
        $nowDate = Carbon::now();
        $hplDate = Carbon::parse($hpl);
        return $hplDate->diffInDays();
    }

    // analysis result
    public function getBmiDesc($length, $weight, $obesityThreshold, $overThreshold, $normalThreshold) {
        try {
            $bmi = $weight / pow(($length / 100), 2);
            if ($bmi < $normalThreshold) {
                return [
                    'type' => 1,
                    'desc' => 'Under Wight',
                    'is_normal' => false,
                ];
            } else if ($bmi >= $normalThreshold && $bmi < $overThreshold) {
                return [
                    'type' => 2,
                    'desc' => 'Normal Weight',
                    'is_normal' => true,
                ];
            } else if ($bmi >= $overThreshold && $bmi < $obesityThreshold) {
                return [
                    'type' => 3,
                    'desc' => 'Over Weight',
                    'is_normal' => false,
                ];
            } else if($bmi >= $obesityThreshold) {
                return [
                    'type' => 4,
                    'desc' => 'Obesitas',
                    'is_normal' => false,
                ];
            }
        } catch (\Exception $e) {}
        return [
            'type' => 5,
            'desc' => 'N/A',
            'is_normal' => false,
        ];
    }

    public function getMomPulseDesc($val, $topThreshold) {
        if($val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Normal',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 2,
                'desc' => 'Di atas normal',
                'is_normal' => false,
            ];
    }

    public function getTfuDesc($val, $topThreshold, $bottomThreshold) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Normal',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 1,
                'desc' => 'Tidak normal',
                'is_normal' => false,
            ];
    }

    public function getDjjDesc($val, $topThreshold, $bottomThreshold) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Normal',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 1,
                'desc' => 'Tidak normal',
                'is_normal' => false,
            ];
    }

    public function getBabyMovementDesc($val, $bottomThreshold) {
        if($val >= $bottomThreshold)
            return [
                'type' => 1,
                'desc' => 'Aktif',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 2,
                'desc' => 'Tidak Aktif',
                'is_normal' => false,
            ];
    }


    public function getPregnancyData($prop) {
        $q = 'select sp.' . $prop . ', sp.week from service_statement_ibu_hamil_periksa sp
                                    join service_statement_ibu_hamil s on s.id = sp.trisemester_id
                                    join kia_identitas_anak ka on ka.id = s.kia_anak_id
                                    where ka.kia_ibu_id = 1 order by sp.week';
        return DB::select($q);
    }


    // anaku analysis
    public function getAnakuAnalysisDesc($bottomThreshold, $topThreshold, $val) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'desc' => 'Normal',
                'is_normal' => true
            ];

        return [
            'desc' => 'Tidak Normal',
            'is_normal' => false
        ];
    }
}
