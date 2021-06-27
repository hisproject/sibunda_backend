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

    public function getBmiDesc($length, $weight, $obesityThreshold, $overThreshold, $normalThreshold) {
        try {
            $bmi = $weight / pow(($length / 100), 2);
            if ($bmi < $normalThreshold) {
                return [
                    'type' => 1,
                    'desc' => 'Under Wight'
                ];
            } else if ($bmi >= $normalThreshold && $bmi < $overThreshold) {
                return [
                    'type' => 2,
                    'desc' => 'Normal Weight'
                ];
            } else if ($bmi >= $overThreshold && $bmi < $obesityThreshold) {
                return [
                    'type' => 3,
                    'desc' => 'Over Weight'
                ];
            } else if($bmi >= $obesityThreshold) {
                return [
                    'type' => 4,
                    'desc' => 'Obesitas'
                ];
            }
        } catch (\Exception $e) {}
        return [
            'type' => 5,
            'desc' => 'N/A'
        ];
    }

    public function getMomPulseDesc($val, $topThreshold) {
        if($val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Normal'
            ];
        else
            return [
                'type' => 2,
                'desc' => 'Di atas normal'
            ];
    }

    public function getTfuDesc($val, $topThreshold, $bottomThreshold) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Normal'
            ];
        else
            return [
                'type' => 1,
                'desc' => 'Tidak normal'
            ];
    }

    public function getDjjDesc($val, $topThreshold, $bottomThreshold) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Normal'
            ];
        else
            return [
                'type' => 1,
                'desc' => 'Tidak normal'
            ];
    }

    public function getBabyMovementDesc($val, $bottomThreshold) {
        if($val >= $bottomThreshold)
            return [
                'type' => 1,
                'desc' => 'Aktif'
            ];
        else
            return [
                'type' => 2,
                'desc' => 'Tidak Aktif'
            ];
    }


    public function getPregnancyData($prop) {
        $q = 'select sp.' . $prop . ', sp.week from service_statement_ibu_hamil_periksa sp
                                    join service_statement_ibu_hamil s on s.id = sp.trisemester_id
                                    join kia_identitas_anak ka on ka.id = s.kia_anak_id
                                    where ka.kia_ibu_id = 1 order by sp.week';
        return DB::select($q);
    }
}
