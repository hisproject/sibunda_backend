<?php


namespace App\Http\Controllers\Mobile;


use App\Models\AnakParamBbPb;
use App\Models\AnakParamBbUsia;
use App\Models\AnakParamImt;
use App\Models\AnakParamLingkarKepala;
use App\Models\AnakParamPbUsia;
use App\Models\BabyMovementGrowthParam;
use App\Models\CovidFormAns;
use App\Models\DjjGrowthParam;
use App\Models\FetusGrowthParam;
use App\Models\KiaIdentitasIbu;
use App\Models\MomPulseGrowthParam;
use App\Models\ServiceStatementAnakMonthlyCheckup;
use App\Models\ServiceStatementIbuHamilPeriksa;
use App\Models\TfuGrowthParam;
use App\Models\WeightGrowthParam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

    protected function getHealthOverview() {
        $ibu = KiaIdentitasIbu::where('user_id', Auth::id())->with('kia_anak')->first();
        $res = [];

        foreach($ibu->kia_anak as $anak) {
            if($anak->is_janin) {
                $formData = ServiceStatementIbuHamilPeriksa::whereHas('trisemester', function($q) use (&$anak) {
                    $q->where('kia_anak_id', $anak->id);
                })->orderBy('created_at', 'desc')->first();

                if(empty($formData))
                    continue;

                $weightGrowthParam = WeightGrowthParam::where('week', $formData->week)->first();
                $momPulseGrowthParam = MomPulseGrowthParam::where('week', $formData->week)->first();
                $tfuGrowthParam = TfuGrowthParam::where('week', $formData->week)->first();
                $djjGrowthParam = DjjGrowthParam::where('week', $formData->week)->first();
                $babyMovementGrowthParam = BabyMovementGrowthParam::where('week', $formData->week)->first();

                $dataBmi = $this->getBmiDesc(
                    $formData->tb ?? 0,
                    $formData->bb ?? 0,
                    $weightGrowthParam->bottom_obesity_threshold ?? 0,
                    $weightGrowthParam->bottom_over_threshold ?? 0,
                    $weightGrowthParam->bottom_normal_threshold ?? 0,
                );
                $dataMomPulse = $this->getMomPulseDesc($formData->map, $momPulseGrowthParam->top_threshold ?? 0);
                $dataTfu = $this->getTfuDesc($formData->tfu, $tfuGrowthParam->top_threshold ?? 0, $tfuGrowthParam->bottom_threshold ?? 0);
                $dataDjj = $this->getDjjDesc($formData->djj, $djjGrowthParam->top_threshold ?? 0, $djjGrowthParam->bottom_threshold ?? 0);
                $dataBabyMovement = $this->getBabyMovementDesc($formData->gerakan_bayi, $babyMovementGrowthParam->bottom_threshold ?? 0);

                $dataBmi['img_url'] = 'https://sibunda.amirmb.com/res/img/kehamilanku/analisis_bb.png';
                $dataMomPulse['img_url'] = 'https://sibunda.amirmb.com/res/img/kehamilanku/analisis_denyut_nadi.png';
                $dataTfu['img_url'] = 'https://sibunda.amirmb.com/res/img/kehamilanku/analisis_tfu.png';
                $dataDjj['img_url'] = 'https://sibunda.amirmb.com/res/img/kehamilanku/analisis_djj.png';
                $dataBabyMovement['img_url'] = 'https://sibunda.amirmb.com/res/img/kehamilanku/analisis_gerakan.png';

                array_push($res, $dataBmi);
                array_push($res, $dataMomPulse);
                array_push($res, $dataTfu);
                array_push($res, $dataDjj);
                array_push($res, $dataBabyMovement);
            } else {
                $formData = ServiceStatementAnakMonthlyCheckup::whereHas('year', function($q) use (&$anak) {
                    $q->where('kia_anak_id', $anak->id);
                })->orderBy('created_at', 'desc')->first();

                if(empty($formData))
                    continue;

                $isLaki = $formData->year->kia_identitas_anak->jenis_kelamin == 'L';
                $tb = $formData->tb;
                $diff = $tb - (int) $tb;

                if($diff > 0.25 && $diff < 0.75)
                    $tb = (int) $tb + 0.5;
                else if($diff <= 0.25)
                    $tb = (int) $tb;
                else
                    $tb = (int) $tb + 1;

                $anakParamBbUsia = AnakParamBbUsia::where('month', $formData->month)
                    ->where('is_laki', $isLaki)
                    ->first();
                $anakParamPbUsia = AnakParamPbUsia::where('month', $formData->month)
                    ->where('is_laki', $isLaki)
                    ->first();
                $anakParamBbPb = AnakParamBbPb::where('pb', $tb)
                    ->where('is_laki', $isLaki)
                    ->first();
                $anakParamLingkarKepala = AnakParamLIngkarKepala::where('month', $formData->month)
                    ->where('is_laki', $isLaki)
                    ->first();
                $anakParamImt = AnakParamImt::where('month', $formData->month)
                    ->where('is_laki', $isLaki)
                    ->first();

                $dataBbUmur = $this->getAnakuAnalysisDesc(
                    $anakParamBbUsia->minus_2_sd ?? -1,
                    $anakParamBbUsia->plus_1_sd ?? -1,
                    $formData->bb ?? -1,
                    'Selamat Bunda! Berat badan bayi normal ya Bun menurut usia bayi.',
                    'Bunda, berat badan bayi tidak normal ya Bun menurut usia bayi.');
                $dataPbUmur = $this->getAnakuAnalysisDesc(
                    $anakParamPbUsia->minus_2_sd ?? -1,
                    $anakParamPbUsia->plus_3_sd ?? -1,
                    $formData->tb ?? -1,
                    'Selamat Bunda! Panjang badan atau tinggi badan menurut usia bayi normal.',
                    'Bunda, panjang badan atau tinggi badan menurut usia bayi tidak normal.');
                $dataBbPb = $this->getAnakuAnalysisDesc(
                    $anakParamBbPb->minus_2_sd ?? -1,
                    $anakParamBbPb->plus_1_sd ?? -1,
                    $formData->bb ?? -1,
                    'Selamat Bunda! Berat badan bayi menurut panjang/tinggi badan normal.',
                    'Bunda, berat badan bayi menurut panjang/tinggi badan tidak normal.');
                $dataLingkarKepala = $this->getAnakuAnalysisDesc(
                    $anakParamLingkarKepala->minus_2_sd ?? -1,
                    $anakParamLingkarKepala->plus_1_sd ?? -1,
                    $formData->lingkar_kepala ?? -1,
                    'Selamat Bunda! Ukuran lingkar kepala bayi  normal menurut usia bayi.',
                    'Bunda, ukuran lingkar kepala bayi tidak normal menurut usia bayi.');
                $dataImt = $this->getAnakuAnalysisDesc(
                    $anakParamImt->minus_2_sd ?? -1,
                    $anakParamImt->plus_1_sd ?? -1,
                    $formData->imt ?? -1,
                    'Selamat Bunda! Indeks massa tubuh bayi normal ya Bun',
                    'Bunda, indeks massa tubuh bayi tidak normal ya Bun');

                $dataBbUmur['img_url'] = 'https:://sibunda.amirmb.com/res/img/anaku/analisi_bb_umur.png';
                $dataPbUmur['img_url'] = 'https:://sibunda.amirmb.com/res/img/anaku/analisi_pb_umur.png';
                $dataBbPb['img_url'] = 'https:://sibunda.amirmb.com/res/img/anaku/analisi_bb_pb.png';
                $dataLingkarKepala['img_url'] = 'https:://sibunda.amirmb.com/res/img/anaku/analisi_lingkar_kepala.png';
                $dataImt['img_url'] = 'https:://sibunda.amirmb.com/res/img/anaku/analisi_imt.png';

                array_push($res, $dataBbUmur);
                array_push($res, $dataPbUmur);
                array_push($res, $dataBbPb);
                array_push($res, $dataLingkarKepala);
                array_push($res, $dataImt);
            }
        }

        return $res;
    }
}
