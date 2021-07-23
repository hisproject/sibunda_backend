<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\AnakParamBbPb;
use App\Models\AnakParamBbUsia;
use App\Models\AnakParamImt;
use App\Models\AnakParamKms;
use App\Models\AnakParamLingkarKepala;
use App\Models\AnakParamPbUsia;
use App\Models\AnakParamPerkembangan;
use App\Models\KiaIdentitasAnak;
use App\Models\PerkembanganQuestionnaire;
use App\Models\ServiceStatementAnakImmunization;
use App\Models\ServiceStatementAnakMonthlyCheckup;
use App\Models\ServiceStatementAnakNeonatusKn1;
use App\Models\ServiceStatementAnakNeonatusKn2;
use App\Models\ServiceStatementAnakNeonatusKn3;
use App\Models\ServiceStatementAnakNeonatusSixHours;
use App\Models\ServiceStatementMonthlyPerkembangan;
use App\Utils\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// bayiku/anak
class BayikuController extends Controller
{
    use GlobalDataHelper;
    //
    public function getOverview() {
        try {
            $kiaIbu = Auth::user()->kia_ibu;
            $anak = KiaIdentitasAnak::select('id', 'nama', 'anak_ke', 'nik')->with('years')
                ->where('kia_ibu_id', $kiaIbu->id)->where('is_janin', false)->get();
            foreach ($anak as $a) {
                $a->age = $this->getChildAgeDesc($a->tanggal_lahir ?? null);
            }

            return Constants::successResponseWithNewValue('data', $anak);
        } catch(\Exception $e) {
            return Constants::errorResponse($e->getMessage());
        }
    }

    public function createMonthlyReport(Request $request) {
        $request->validate([
            'year_id' => 'integer|required',
            'month' => 'integer|required',
            'date' => 'date',
            'location' => 'string',
            'pemeriksa' => 'string',
            'age' => 'integer',
            'bb' => 'numeric',
            'tb' => 'numeric',
            'lingkar_kepala' => 'numeric',
            'imt' => 'numeric',
            'perkembangan_ans' => 'array',
            'perkembangan_ans.*.q_id' => 'integer',
            'perkembangan_ans.*.ans' => 'integer'
        ]);

        DB::beginTransaction();
        try {
            $checkupData = ServiceStatementAnakMonthlyCheckup::where('year_id', $request->year_id)
                                                                ->where('month', $request->month)->first();
            if(empty($checkupData))
                $checkupData = new ServiceStatementAnakMonthlyCheckup();

            $checkupData->year_id = $request->year_id;
            $checkupData->month = $request->month;
            $checkupData->date = $request->date;
            $checkupData->location = $request->location;
            $checkupData->pemeriksa = $request->pemeriksa;
            $checkupData->age = $request->age;
            $checkupData->bb = $request->bb;
            $checkupData->tb = $request->tb;
            $checkupData->lingkar_kepala = $request->lingkar_kepala;
            $checkupData->imt = $request->imt;
            $checkupData->save();

            if(!empty($request->perkembangan_ans))
                $this->createPerkembanganQuestionnaireAns($checkupData->id, $request->perkembangan_ans);

            DB::commit();
            return Constants::successResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return Constants::errorResponse($e->getMessage());
        }
    }

    private function createPerkembanganQuestionnaireAns($report, $perkembanganAns) {
        foreach($perkembanganAns as $ans) {
            ServiceStatementMonthlyPerkembangan::create([
                'monthly_report_id' => $report,
                'questionnaire_id' => $ans['q_id'],
                'ans' => $ans['ans']
            ]);
        }
    }

    public function getMonthlyReport(Request $request) {
        $request->validate([
            'month' => 'integer|required',
            'year_id' => 'integer|required'
        ]);

        $data = ServiceStatementAnakMonthlyCheckup::where('month', $request->month)
                                                    ->where('year_id', $request->year_id)->first();

        if(empty($data))
            return Constants::errorResponse('no matching data for month ' . $request->month);

        return $data;
    }

    public function getMonthlyReportAnalysis(Request $request) {
        $request->validate([
            'month' => 'integer|required',
            'year_id' => 'integer|required'
        ]);

        $monthData = ServiceStatementAnakMonthlyCheckup::where('month', $request->month)
            ->where('year_id', $request->year_id)->first();

        if(empty($monthData))
            return Constants::errorResponse('no matching data for month : ' . $request->month);

        $isLaki = $monthData->year->kia_identitas_anak->jenis_kelamin == 'L';
        $tb = $monthData->tb;
        $diff = $tb - (int) $tb;

        if($diff > 0.25 && $diff < 0.75)
            $tb = (int) $tb + 0.5;
        else if($diff <= 0.25)
            $tb = (int) $tb;
        else
            $tb = (int) $tb + 1;

        $anakParamBbUsia = AnakParamBbUsia::where('month', $monthData->month)
                                            ->where('is_laki', $isLaki)
                                            ->first();
        $anakParamPbUsia = AnakParamPbUsia::where('month', $monthData->month)
            ->where('is_laki', $isLaki)
            ->first();
        $anakParamBbPb = AnakParamBbPb::where('pb', $tb)
            ->where('is_laki', $isLaki)
            ->first();
        $anakParamLingkarKepala = AnakParamLIngkarKepala::where('month', $monthData->month)
                                                            ->where('is_laki', $isLaki)
                                                            ->first();
        $anakParamImt = AnakParamImt::where('month', $monthData->month)
                                        ->where('is_laki', $isLaki)
                                        ->first();

        $res['bb_usia_desc'] = $this->getAnakuAnalysisDesc(
            $anakParamBbUsia->minus_2_sd ?? -1,
            $anakParamBbUsia->plus_1_sd ?? -1,
            $monthData->bb ?? -1,
            'Selamat Bunda! Berat badan bayi normal ya Bun menurut usia bayi.',
            'Bunda, berat badan bayi tidak normal ya Bun menurut usia bayi.');

        $res['pb_usia_desc'] = $this->getAnakuAnalysisDesc(
            $anakParamPbUsia->minus_2_sd ?? -1,
            $anakParamPbUsia->plus_3_sd ?? -1,
            $monthData->tb ?? -1,
            'Selamat Bunda! Panjang badan atau tinggi badan menurut usia bayi normal.',
            'Bunda, panjang badan atau tinggi badan menurut usia bayi tidak normal.');
        $res['bb_pb_desc'] = $this->getAnakuAnalysisDesc(
            $anakParamBbPb->minus_2_sd ?? -1,
            $anakParamBbPb->plus_1_sd ?? -1,
            $monthData->bb ?? -1,
            'Selamat Bunda! Berat badan bayi menurut panjang/tinggi badan normal.',
            'Bunda, berat badan bayi menurut panjang/tinggi badan tidak normal.');
        $res['lingkar_kepala_desc'] = $this->getAnakuAnalysisDesc(
            $anakParamLingkarKepala->minus_2_sd ?? -1,
            $anakParamLingkarKepala->plus_1_sd ?? -1,
            $monthData->lingkar_kepala ?? -1,
            'Selamat Bunda! Ukuran lingkar kepala bayi  normal menurut usia bayi.',
        'Bunda, ukuran lingkar kepala bayi tidak normal menurut usia bayi.');
        $res['imt_desc'] = $this->getAnakuAnalysisDesc(
            $anakParamImt->minus_2_sd ?? -1,
            $anakParamImt->plus_1_sd ?? -1,
            $monthData->imt ?? -1,
            'Selamat Bunda! Indeks massa tubuh bayi normal ya Bun',
            'Bunda, indeks massa tubuh bayi tidak normal ya Bun');

        return Constants::successResponseWithNewValue('data', $res);
    }

    public function getMonthlyPerkembanganQuestionnaire($month) {
        $q = PerkembanganQuestionnaire::where('month_start', '<=', $month)
                                        ->where('month_until', '>=', $month)
                                        ->orderBy('no')->get();

        return $q;
    }

    public function createNeonatusSixHours(Request $request) {
        $data = $request->validate([
            'bb' => 'numeric|required',
            'tb' => 'numeric|required',
            'lingkar_kepala' => 'numeric|required',
            'q_imd' => 'integer|required',
            'q_vit_k1' => 'integer|required',
            'q_salep' => 'integer|required',
            'q_imunisasi_hb' => 'integer|required',
            'date' => 'date|required',
            'time' => 'date_format:H:i|required',
            'no_batch' => 'string|min:0',
            'dirujuk_ke' => 'string|min:0',
            'petugas' => 'string|min:0',
            'catatan_penting' => 'string|min:0',
            'monthly_checkup_id' => 'integer|required'
        ]);

        ServiceStatementAnakNeonatusSixHours::create($data);

        return Constants::successResponse();
    }

    public function createNeonatusKn1(Request $request) {
        $data = $request->validate([
            'q_menyusu' => 'integer|required',
            'q_tali_pusat' => 'integer|required',
            'q_vit_k1' => 'integer|required',
            'q_salep' => 'integer|required',
            'q_imunisasi_hb' => 'integer|required',
            'date' => 'date|required',
            'time' => 'date_format:H:i|required',
            'no_batch' => 'string|min:0',
            'tb' => 'numeric|required',
            'bb' => 'numeric|required',
            'lingkar_kepala' => 'numeric|required',
            'q_skrining_hipotiroid_kongenital' => 'integer|required',
            'masalah' => 'string|min:0',
            'dirujuk_ke' => 'string|min:0',
            'petugas' => 'string|min:0',
            'catatan_penting' => 'string|min:0',
            'monthly_checkup_id' => 'integer|required'
        ]);

        ServiceStatementAnakNeonatusKn1::create($data);

        return Constants::successResponse();
    }

    public function createNeonatusKn2(Request $request) {
        $data = $request->validate([
            'q_menyusu' => 'integer|required',
            'q_tali_pusat' => 'integer|required',
            'q_tanda_bahaya' => 'integer|required',
            'q_identifikasi_kuning' => 'integer|required',
            'q_imunisasi_hb' => 'integer|required',
            'date' => 'date|required',
            'time' => 'date_format:H:i|required',
            'no_batch' => 'string|min:0',
            'tb' => 'numeric|required',
            'bb' => 'numeric|required',
            'lingkar_kepala' => 'numeric|required',
            'q_skrining_hipotiroid_kongenital' => 'integer|required',
            'masalah' => 'string|min:0',
            'dirujuk_ke' => 'string|min:0',
            'petugas' => 'string|min:0',
            'catatan_penting' => 'string|min:0',
            'monthly_checkup_id' => 'integer|required'
        ]);

        ServiceStatementAnakNeonatusKn2::create($data);

        return Constants::successResponse();
    }

    public function createNeonatusKn3(Request $request) {
        $data = $request->validate([
            'q_menyusu' => 'integer|required',
            'q_tali_pusat' => 'integer|required',
            'q_vit_k1' => 'integer|required',
            'q_salep' => 'integer|required',
            'q_imunisasi_hb' => 'integer|required',
            'q_kuning1' => 'integer|required',
            'q_kuning2' => 'integer|required',
            'q_kuning3' => 'integer|required',
            'q_kuning4' => 'integer|required',
            'q_kuning5' => 'integer|required',
            'masalah' => 'string|min:0',
            'dirujuk_ke' => 'string|min:0',
            'petugas' => 'string|min:0',
            'catatan_penting' => 'string|min:0',
            'monthly_checkup_id' => 'integer|required'
        ]);

        ServiceStatementAnakNeonatusKn3::create($data);

        return Constants::successResponse();
    }

    public function getImmunization($kiaAnakId) {
        $data = ServiceStatementAnakImmunization::where('kia_anak_id', $kiaAnakId)
                                                    ->orderBy('immunization_id')->with('immunization')->get();

        return Constants::successResponseWithNewValue('data', $data);
    }

    public function createImmunization(Request $request) {
        $request->validate([
            'id' => 'integer|required',
            'date' => 'date|required',
            'location' => 'required',
            'pic' => 'required',
            'no_batch' => 'required',
        ]);

        $data = ServiceStatementAnakImmunization::find($request->id);
        $data->date = $request->date;
        $data->location = $request->location;
        $data->pic = $request->pic;
        $data->no_batch = $request->no_batch;
        $data->save();

        return Constants::successResponse();
    }

    // graphs
    public function getKmsGraphData($kiaAnakId) {
        try {
            $isLaki = KiaIdentitasAnak::find($kiaAnakId)->jenis_kelamin == 'L';
            $bbUsiaParam = AnakParamKms::where('is_laki', $isLaki)->orderBy('month')->get();
            $insertedData = $this->getBayiAnakData('bb', $kiaAnakId);
            $res = [];
            $currDataIndex = 0;
            $currDataLen = count($insertedData);

            foreach ($bbUsiaParam as $param) {
                $data = [
                    'month' => (int) $param->month,
                    'minus_3_sd' => (double) $param->minus_3_sd,
                    'minus_2_sd' => (double) $param->minus_2_sd,
                    'minus_1_sd' => (double) $param->minus_1_sd,
                    'median' => (double) $param->median,
                    'plus_1_sd' => (double) $param->plus_1_sd,
                    'plus_2_sd' => (double) $param->plus_2_sd,
                    'plus_3_sd' => (double) $param->plus_3_sd
                ];

                if ($currDataIndex < $currDataLen &&
                    $param->month == $insertedData[$currDataIndex]->month)
                    $data['input'] = (double) $insertedData[$currDataIndex++]->bb;
                else
                    $data['input'] = -1;

                array_push($res, $data);
            }

            $desc = null;
            try {
                $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
                $age = $this->getChildAge($kiaAnak->tanggal_lahir);
                $paramByAge = AnakParamKms::where('month', $age)->where('is_laki', $isLaki)->first();
                $desc = $this->getBayiAnakGraphDesc(
                    'bb',
                    $age,
                    $kiaAnakId,
                    $paramByAge->minus_2_sd ?? -1,
                    $paramByAge->plus_1_sd ?? -1,
                    'Selamat Bunda! Berat badan bayi dan kenaikannya normal ya Bun menurut usia bayi.',
                    'Bunda, berat badan bayi dan kenaikannya tidak normal ya Bun');
            } catch (\Exception $e) {}

            $resData['data'] = $res;
            $resData['desc'] = $desc;

            return Constants::successResponseWithNewValue('data', $resData);
        } catch (\Exception $e) {
            $resData['data'] = [];
            $resData['desc'] = [
                'desc' => null,
                'is_normal' => false
            ];
            return Constants::successResponseWithNewValue('data', $resData);
        }
    }

    public function getBbUsiaGraphData($kiaAnakId) {
        try {
            $isLaki = KiaIdentitasAnak::find($kiaAnakId)->jenis_kelamin == 'L';
            $bbUsiaParam = AnakParamBbUsia::where('is_laki', $isLaki)->orderBy('month')->get();
            $insertedData = $this->getBayiAnakData('bb', $kiaAnakId);
            $res = [];
            $currDataIndex = 0;
            $currDataLen = count($insertedData);

            foreach ($bbUsiaParam as $param) {
                $data = [
                    'month' => (int) $param->month,
                    'minus_3_sd' => (double) $param->minus_3_sd,
                    'minus_2_sd' => (double) $param->minus_2_sd,
                    'minus_1_sd' => (double) $param->minus_1_sd,
                    'median' => (double) $param->median,
                    'plus_1_sd' => (double) $param->plus_1_sd,
                    'plus_2_sd' => (double) $param->plus_2_sd,
                    'plus_3_sd' => (double) $param->plus_3_sd
                ];

                if ($currDataIndex < $currDataLen &&
                    $param->month == $insertedData[$currDataIndex]->month)
                    $data['input'] = (double) $insertedData[$currDataIndex++]->bb;
                else
                    $data['input'] = -1;

                array_push($res, $data);
            }

            $desc = null;
            try {
                $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
                $age = $this->getChildAge($kiaAnak->tanggal_lahir);
                $paramByAge = AnakParamKms::where('month', $age)->where('is_laki', $isLaki)->first();
                $desc = $this->getBayiAnakGraphDesc(
                    'bb',
                    $age,
                    $kiaAnakId,
                    $paramByAge->minus_2_sd ?? -1,
                    $paramByAge->plus_1_sd ?? -1,
                    'Selamat Bunda! Berat badan bayi normal ya Bun menurut usia bayi.',
                    'Bunda, berat badan bayi tidak normal ya Bun menurut usia bayi.');
            } catch (\Exception $e) {}

            $resData['data'] = $res;
            $resData['desc'] = $desc;

            return Constants::successResponseWithNewValue('data', $resData);
        } catch (\Exception $e) {
            $resData['data'] = [];
            $resData['desc'] = [
                'desc' => null,
                'is_normal' => false
            ];
            return Constants::successResponseWithNewValue('data', $resData);
        }
    }

    public function getPbUsiaGraphData($kiaAnakId) {
        try {
            $isLaki = KiaIdentitasAnak::find($kiaAnakId)->jenis_kelamin == 'L';
            $pbUsiaParam = AnakParamPbUsia::where('is_laki', $isLaki)->orderBy('month')->get();
            $insertedData = $this->getBayiAnakData('tb', $kiaAnakId);
            $res = [];
            $currDataIndex = 0;
            $currDataLen = count($insertedData);

            foreach ($pbUsiaParam as $param) {
                $data = [
                    'month' => (int) $param->month,
                    'minus_3_sd' => (double) $param->minus_3_sd,
                    'minus_2_sd' => (double) $param->minus_2_sd,
                    'minus_1_sd' => (double) $param->minus_1_sd,
                    'median' => (double) $param->median,
                    'plus_1_sd' => (double) $param->plus_1_sd,
                    'plus_2_sd' => (double) $param->plus_2_sd,
                    'plus_3_sd' => (double) $param->plus_3_sd
                ];

                if ($currDataIndex < $currDataLen &&
                    $param->month == $insertedData[$currDataIndex]->month)
                    $data['input'] = (double) $insertedData[$currDataIndex++]->tb;
                else
                    $data['input'] = -1;

                array_push($res, $data);
            }

            $desc = null;
            try {
                $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
                $age = $this->getChildAge($kiaAnak->tanggal_lahir);
                $paramByAge = AnakParamKms::where('month', $age)->where('is_laki', $isLaki)->first();
                $desc = $this->getBayiAnakGraphDesc(
                    'tb',
                    $age,
                    $kiaAnakId,
                    $paramByAge->minus_2_sd ?? -1,
                    $paramByAge->plus_1_sd ?? -1,
                    'Selamat Bunda! Panjang badan atau tinggi badan normal ya Bun menurut usia bayi.',
                    'Bunda, panjang badan atau tinggi badan menurut usia bayi tidak normal.');
            } catch (\Exception $e) {}

            $resData['data'] = $res;
            $resData['desc'] = $desc;

            return Constants::successResponseWithNewValue('data', $resData);
        } catch (\Exception $e) {
            $resData['data'] = [];
            $resData['desc'] = [
                'desc' => null,
                'is_normal' => false
            ];
            return Constants::successResponseWithNewValue('data', $resData);
        }
    }

    public function getBbPbGraphData($kiaAnakId) {
        try {
            $isLaki = KiaIdentitasAnak::find($kiaAnakId)->jenis_kelamin == 'L';
            $bbPbParam = AnakParamBbPb::where('is_laki', $isLaki)->orderBy('pb')->get();
            $insertedData = $this->getBayiAnakDataByTb('bb', $kiaAnakId);
            $res = [];
            $currDataIndex = 0;
            $currDataLen = count($insertedData);

            foreach ($bbPbParam as $param) {
                $data = [
                    'pb' => (double) $param->pb,
                    'minus_3_sd' => (double) $param->minus_3_sd,
                    'minus_2_sd' => (double) $param->minus_2_sd,
                    'minus_1_sd' => (double) $param->minus_1_sd,
                    'median' => (double) $param->median,
                    'plus_1_sd' => (double) $param->plus_1_sd,
                    'plus_2_sd' => (double) $param->plus_2_sd,
                    'plus_3_sd' => (double) $param->plus_3_sd
                ];

                if ($currDataIndex < $currDataLen) {
                    $tb = $insertedData[$currDataIndex]->tb;
                    $diff = $tb - (int) $tb;

                    if($diff > 0.25 && $diff < 0.75)
                        $tb = (int) $tb + 0.5;
                    else if($diff <= 0.25)
                        $tb = (int) $tb;
                    else
                        $tb = (int) $tb + 1;

                    if ($param->pb == $tb)
                        $data['input'] = (double) $insertedData[$currDataIndex ++]->bb;
                    else
                        $data['input'] = -1;
                } else
                    $data['input'] = -1;

                array_push($res, $data);
            }

            $desc = null;
            try {
                $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
                $age = $this->getChildAge($kiaAnak->tanggal_lahir);
                $paramByAge = AnakParamKms::where('month', $age)->where('is_laki', $isLaki)->first();
                $desc = $this->getBayiAnakGraphDesc(
                    'tb',
                    $age,
                    $kiaAnakId,
                    $paramByAge->minus_2_sd ?? -1,
                    $paramByAge->plus_1_sd ?? -1,
                    'Selamat Bunda! Panjang badan atau tinggi badan normal ya Bun menurut usia bayi.',
                    'Bunda, panjang badan atau tinggi badan menurut usia bayi tidak normal.');
            } catch (\Exception $e) {}

            $resData['data'] = $res;
            $resData['desc'] = $desc;

            return Constants::successResponseWithNewValue('data', $resData);
        } catch (\Exception $e) {
            $resData['data'] = [];
            $resData['desc'] = [
                'desc' => null,
                'is_normal' => false
            ];
            return Constants::successResponseWithNewValue('data', $resData);
        }
    }

    public function getLingkarKepalaGraphData($kiaAnakId) {
        try {
            $isLaki = KiaIdentitasAnak::find($kiaAnakId)->jenis_kelamin == 'L';
            $lingkarKepalaParam = AnakParamLingkarKepala::where('is_laki', $isLaki)->orderBy('month')->get();
            $insertedData = $this->getBayiAnakData('lingkar_kepala', $kiaAnakId);
            $res = [];
            $currDataIndex = 0;
            $currDataLen = count($insertedData);

            foreach ($lingkarKepalaParam as $param) {
                $data = [
                    'month' => (int) $param->month,
                    'minus_3_sd' => (double) $param->minus_3_sd,
                    'minus_2_sd' => (double) $param->minus_2_sd,
                    'minus_1_sd' => (double) $param->minus_1_sd,
                    'median' => (double) $param->median,
                    'plus_1_sd' => (double) $param->plus_1_sd,
                    'plus_2_sd' => (double) $param->plus_2_sd,
                    'plus_3_sd' => (double) $param->plus_3_sd
                ];

                if ($currDataIndex < $currDataLen &&
                    $param->month == $insertedData[$currDataIndex]->month)
                    $data['input'] = (double) $insertedData[$currDataIndex++]->lingkar_kepala;
                else
                    $data['input'] = -1;

                array_push($res, $data);
            }

            $desc = null;
            try {
                $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
                $age = $this->getChildAge($kiaAnak->tanggal_lahir);
                $paramByAge = AnakParamKms::where('month', $age)->where('is_laki', $isLaki)->first();
                $desc = $this->getBayiAnakGraphDesc(
                    'lingkar_kepala',
                    $age,
                    $kiaAnakId,
                    $paramByAge->minus_2_sd ?? -1,
                    $paramByAge->plus_1_sd ?? -1,
                    'Selamat Bunda! Ukuran lingkar kepala bayi normal ya Bun menurut usia bayi.',
                    'Bunda, ukuran lingkar kepala bayi tidak normal menurut usia bayi.');
            } catch (\Exception $e) {}

            $resData['data'] = $res;
            $resData['desc'] = $desc;

            return Constants::successResponseWithNewValue('data', $resData);
        } catch (\Exception $e) {
            $resData['data'] = [];
            $resData['desc'] = [
                'desc' => null,
                'is_normal' => false
            ];
            return Constants::successResponseWithNewValue('data', $resData);
        }
    }

    public function getImtGraphData($kiaAnakId) {
        try {
            $isLaki = KiaIdentitasAnak::find($kiaAnakId)->jenis_kelamin == 'L';
            $imtParam = AnakParamImt::where('is_laki', $isLaki)->orderBy('month')->get();
            $insertedData = $this->getBayiAnakData('imt', $kiaAnakId);
            $res = [];
            $currDataIndex = 0;
            $currDataLen = count($insertedData);

            foreach ($imtParam as $param) {
                $data = [
                    'month' => (int) $param->month,
                    'minus_3_sd' => (double) $param->minus_3_sd,
                    'minus_2_sd' => (double) $param->minus_2_sd,
                    'minus_1_sd' => (double) $param->minus_1_sd,
                    'median' => (double) $param->median,
                    'plus_1_sd' => (double)$param->plus_1_sd,
                    'plus_2_sd' => (double)$param->plus_2_sd,
                    'plus_3_sd' => (double)$param->plus_3_sd
                ];

                if ($currDataIndex < $currDataLen &&
                    $param->month == $insertedData[$currDataIndex]->month)
                    $data['input'] = (double)$insertedData[$currDataIndex++]->imt;
                else
                    $data['input'] = -1;

                array_push($res, $data);
            }

            $desc = null;
            try {
                $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
                $age = $this->getChildAge($kiaAnak->tanggal_lahir);
                $paramByAge = AnakParamKms::where('month', $age)->where('is_laki', $isLaki)->first();
                $desc = $this->getBayiAnakGraphDesc(
                    'imt',
                    $age,
                    $kiaAnakId,
                    $paramByAge->minus_2_sd ?? -1,
                    $paramByAge->plus_1_sd ?? -1,
                    'Selamat Bunda! Indeks Masa Tubuh bayi normal ya Bun menurut usia bayi.',
                    'Bunda, Indeks Masa Tubuh bayi tidak normal ya Bun');
            } catch (\Exception $e) {}

            $resData['data'] = $res;
            $resData['desc'] = $desc;

            return Constants::successResponseWithNewValue('data', $resData);
        } catch (\Exception $e) {
            $resData['data'] = [];
            $resData['desc'] = [
                'desc' => null,
                'is_normal' => false
            ];
            return Constants::successResponseWithNewValue('data', $resData);
        }
    }

    public function getPerkembanganGraphData($kiaAnakId) {
        $data = [];

        $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
        $age = $this->getChildAge($kiaAnak->tanggal_lahir);
        $currInput = 0;

        for($i = 1; $i <= 72; $i ++) {
            $count = DB::selectOne('select count(p.id) from service_statement_anak_monthly_checkup m
                                    join service_statement_anak_years y on y.id = m.year_id
                                    join service_statement_monthly_perkembangan p on p.monthly_report_id = m.id
                                    where p.ans is true and month = ' . $i . ' and kia_anak_id = ' . $kiaAnakId)->count;
            array_push($data, [
                'month' => $i,
                's_threshold' => 9,
                'm_threshold' => 7,
                'input' => $count
            ]);

            if($age == $i)
                $currInput = $count;
        }

        $desc = $this->getAnakuAnalysisDesc(
            7,
            10,
            $currInput,
            'Selamat Bunda! Perkembangan bayi bunda normal ya Bun',
            'Bunda, Perkembangan bayi tidak normal ya Bun');

        $resData['data'] = $data;
        $resData['desc'] = $desc;

        return Constants::successResponseWithNewValue('data', $data);
    }
}
