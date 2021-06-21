<?php


namespace App\Http\Controllers\Mobile;


use Carbon\Carbon;

trait GlobalDataHelper
{
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
}
