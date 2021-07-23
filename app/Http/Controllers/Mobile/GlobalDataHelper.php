<?php


namespace App\Http\Controllers\Mobile;


use App\Models\CovidFormAns;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GlobalDataHelper
{
    public function getChildAge($bornDateStr, $inMonth = false) {
        try {
            $nowDate = Carbon::now();
            $bornDate = Carbon::parse($bornDateStr);

            if($inMonth)
                return $bornDate->diffInWeeks($nowDate);

            return $bornDate->diffInDays($nowDate);
        } catch (\Exception $e) {
            return 0;
        }
    }

    // return description string
    public function getChildAgeDesc($bornDateStr) {
        try {
            $days = $this->getChildAge($bornDateStr);

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

    public function getHpl($week, $kiaAnakId) {
        return DB::selectOne('select ka.hpl from service_statement_ibu_hamil_periksa c
            join service_statement_ibu_hamil t on t.id = c.trisemester_id
            join kia_identitas_anak ka on ka.id = t.kia_anak_id
            where c.week = ' . $week . ' and ka.id = ' . $kiaAnakId);
    }

    public function getRemainingPregnancyDays($hpl) {
        $nowDate = Carbon::now();
        $hplDate = Carbon::parse($hpl);
        return $hplDate->diffInDays($nowDate);
    }

    // analysis result
    public function getBmiDesc($length, $weight, $obesityThreshold, $overThreshold, $normalThreshold) {
        try {
            $bmi = $weight / pow(($length / 100), 2);
            if ($bmi < $normalThreshold) {
                return [
                    'type' => 1,
                    'desc' => 'Bunda, hasil pengukuran berat badan bunda termasuk Under Weight ya Bun.',
                    'is_normal' => false,
                ];
            } else if ($bmi >= $normalThreshold && $bmi < $overThreshold) {
                return [
                    'type' => 2,
                    'desc' => 'Bunda, hasil pengukuran berat badan bunda termasuk Normal ya Bun.',
                    'is_normal' => true,
                ];
            } else if ($bmi >= $overThreshold && $bmi < $obesityThreshold) {
                return [
                    'type' => 3,
                    'desc' => 'Bunda, hasil pengukuran berat badan bunda termasuk Over Weight ya Bun.',
                    'is_normal' => false,
                ];
            } else if($bmi >= $obesityThreshold) {
                return [
                    'type' => 4,
                    'desc' => 'Bunda, hasil pengukuran berat badan bunda termasuk Obesitas ya Bun.',
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
                'desc' => 'Selamat Bunda! Denyut Jantung Janin Bunda normal ya Bun.',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 2,
                'desc' => 'Denyut Jantung Janin Bunda kurang ya. Silahkan periksa ke faskes ya Bun',
                'is_normal' => false,
            ];
    }

    public function getTfuDesc($val, $topThreshold, $bottomThreshold) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Selamat Bunda! Tinggi Fundus Uterus Bunda normal ya Bun.',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 1,
                'desc' => 'TFU Bunda tidak normal ya Bun. Bisa konsultasikan ke dokter terdekat ya',
                'is_normal' => false,
            ];
    }

    public function getDjjDesc($val, $topThreshold, $bottomThreshold) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'type' => 1,
                'desc' => 'Selamat Bunda! Denyut Nadi bunda normal ya. Jadi Bunda tidak beresiko mengalami preeklamsia.',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 1,
                'desc' => 'Bunda beresiko mengalami preeklamsia. Segera menghubungi dokter ya Bun',
                'is_normal' => false,
            ];
    }

    public function getBabyMovementDesc($val, $bottomThreshold) {
        if($val >= $bottomThreshold)
            return [
                'type' => 1,
                'desc' => 'Selamat Bunda! Gerakan Bayi Bunda sudah aktif dan normal ya Bun',
                'is_normal' => true,
            ];
        else
            return [
                'type' => 2,
                'desc' => 'Gerakan Bayi Bunda kurang aktif. Segera konsultasi ke dokter ya Bun',
                'is_normal' => false,
            ];
    }

    public function getPregnancyData($prop, $kiaAnakId = 1) {
        $q = 'select sp.' . $prop . ', sp.week from service_statement_ibu_hamil_periksa sp
                join service_statement_ibu_hamil s on s.id = sp.trisemester_id
                where s.kia_anak_id = ' . $kiaAnakId . ' order by sp.week';
        return DB::select($q);
    }

    public function getPregnancyGraphDesc($prop, $week, $kiaAnakId, $bottomThreshold, $topThreshold, $normalDesc, $abnormalDesc) {
        $data = DB::selectOne('select c.' . $prop . ' from service_statement_ibu_hamil_periksa c
                join service_statement_ibu_hamil t on t.id = c.trisemester_id
                where c.week = ' . $week . ' and t.kia_anak_id = ' . $kiaAnakId);

        if(empty($data))
           return [
               'desc' => null,
               'is_normal' => false
           ];

        $isNormal = $data->$prop >= $bottomThreshold && $data->prop <= $topThreshold;

        return [
            'desc' => ($isNormal ? $normalDesc : $abnormalDesc),
            'is_normal' => $isNormal
        ];
    }

    public function getBayiAnakData($prop, $kiaAnakId = 2) {
        $q = 'select sp.' . $prop . ', sp.month from service_statement_anak_monthly_checkup sp
                join service_statement_anak_years sy on sy.id = sp.year_id
                where sy.kia_anak_id = ' . $kiaAnakId . ' order by sp.month';

        return DB::select($q);
    }

    public function getBayiAnakGraphDesc($prop, $month, $kiaAnakId, $bottomThreshold, $topThreshold, $normalDesc, $abnormalDesc) {
        $data = DB::selectOne('select c.' . $prop . ' from service_statement_anak_monthly_checkup c
                join service_statement_anak_years t on t.id = c.year_id
                where c.month = ' . $month . ' and t.kia_anak_id = ' . $kiaAnakId);

        if(empty($data))
            return [
                'desc' => null,
                'is_normal' => false
            ];

        return $this->getAnakuAnalysisDesc($bottomThreshold, $topThreshold, $data->$prop, $normalDesc, $abnormalDesc);
    }

    public function getBayiAnakDataByTb($prop, $kiaAnakId = 2) {
        $q = 'select sp.' . $prop . ', sp.tb from service_statement_anak_monthly_checkup sp
                join service_statement_anak_years sy on sy.id = sp.year_id
                where sy.kia_anak_id = ' . $kiaAnakId . ' order by sp.tb';

        return DB::select($q);
    }

    // anaku analysis
    public function getAnakuAnalysisDesc($bottomThreshold, $topThreshold, $val, $normalDesc, $abnormalDesc) {
        if($val >= $bottomThreshold && $val <= $topThreshold)
            return [
                'desc' => $normalDesc,
                'is_normal' => true
            ];

        return [
            'desc' => $abnormalDesc,
            'is_normal' => false
        ];
    }

    // covid checkup analysis
    protected function meetCovidCategory($formId, $q_ids) {
        $mustAns = CovidFormAns::whereIn('q_id', $q_ids)->where('form_id', $formId)->get();
        $mustNotAns = CovidFormAns::whereNotIn('q_id', $q_ids)->where('form_id', $formId)->get();

        foreach($mustAns as $ans)
            if(!$ans->ans)
                return false;

        foreach($mustNotAns as $ans)
            if($ans->ans)
                return false;

        return true;
    }
}
