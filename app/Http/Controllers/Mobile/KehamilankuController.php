<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BabyMovementGrowthParam;
use App\Models\DjjGrowthParam;
use App\Models\FetusGrowthParam;
use App\Models\KiaIdentitasAnak;
use App\Models\KiaIdentitasIbu;
use App\Models\MomPulseGrowthParam;
use App\Models\ServiceStatementIbuHamil;
use App\Models\ServiceStatementIbuHamilPeriksa;
use App\Models\ServiceStatementIbuImmunization;
use App\Models\TfuGrowthParam;
use App\Models\User;
use App\Models\WeightGrowthParam;
use App\Utils\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class KehamilankuController extends Controller
{
    use GlobalDataHelper;
    //
    public function getAnak() {

    }

    public function getOverview() {
        $kiaIbu = Auth::user()->kia_ibu;
        $janin = KiaIdentitasAnak::select('id', 'nama', 'anak_ke', 'hpl')->with('trisemesters')
                            ->where('kia_ibu_id', $kiaIbu->id)->where('is_janin', true)->get();
        foreach($janin as $j) {
            $j->week = $this->getPregnancyAgeInWeek($j->hpl);
            $j->remaining_days = $this->getRemainingPregnancyDays($j->hpl);
            // cek test rekomendasi
            $j->food_recommendations = [
                [
                    'img_url' => 'https://sibunda.amirmb.com/res/img/kehamilanku/rekomendasi_1.png',
                    'food_category' => 'Nasi atau Makanan Pokok',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 5 porsi nasi ya, dengan 1 porsi = 100 gr atau 3/4 gelas nasi',
                ],
                [
                    'img_url' => 'https://sibunda.amirmb.com/res/img/kehamilanku/rekomendasi_2.png',
                    'food_category' => 'Protein Hewani',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 4 porsi protein hewani ya, dengan 1 porsi = 50 gr atau 1 potong sedang ikan, dan 1 porsi = 100 gr atau 1 butir telur ayam',
                ],
                [
                    'img_url' => 'https://sibunda.amirmb.com/res/img/kehamilanku/rekomendasi_3.png',
                    'food_category' => 'Protein Nabati',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 4 porsi protein nabati ya, dengan 1 porsi = 50 gr atau 1 potong sedang tempe, dan 1 porsi = 100 gr atau 2 potong sedang tahu',
                ],
                [
                    'img_url' => 'https://sibunda.amirmb.com/res/img/kehamilanku/rekomendasi_4.png',
                    'food_category' => 'Sayur - sayuran',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 4 porsi sayur ya, dengan 1 porsi = 100 gr atau 1 mangkuk tanpa kuah',
                ],
                [
                    'img_url' => 'https://sibunda.amirmb.com/res/img/kehamilanku/rekomendasi_5.png',
                    'food_category' => 'Buah - buahan',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 4 porsi buah ya, dengan 1 porsi = 100 gr atau 1 potong sedang pisang, dan 1 porsi = 100-190 gr atau 1 potong besar pepaya',
                ],
                [
                    'img_url' => 'https://sibunda.amirmb.com/res/img/kehamilanku/rekomendasi_6.png',
                    'food_category' => 'Minyak / Lemak',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 4 porsi protein nabati ya, dengan 1 porsi = 50 gr atau 1 potong sedang tempe, dan 1 porsi = 100 gr atau 2 potong sedang tahu',
                ],
                [
                    'img_url' => 'https://sibunda.amirmb.com/res/img/kehamilanku/rekomendasi_7.png',
                    'food_category' => 'Gula',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 2 porsi gula ya, dengan 1 porsi = 10 gr atau 1 sendok makan bersumber dari kue-kue manis, minum teh manis dan lain-lainnya',
                ],
            ];
        }

        return Constants::successResponseWithNewValue('data', $janin);
    }

    public function createWeeklyReport(Request $request) {
        $request->validate([
            'week' => 'integer|required',
            'tanggal_periksa' => 'date|required',
            'tempat_periksa' => 'string|required',
            'nama_pemeriksa' => 'string|required',
            'keluhan_bunda' => 'string|required',
            'jenis_kelamin' => 'size:1',
            'tanggal_periksa_kembali' => 'date|required',
            'hpl' => 'date',
            'bb' => 'numeric|required',
            'kenaikan_bb' => 'numeric|required',
            'tb' => 'numeric|required',
            'tfu' => 'numeric|required',
            'djj' => 'numeric|required',
            'sistolik' => 'numeric|required',
            'diastolik' => 'numeric|required',
            'map' => 'numeric|required',
            'gerakan_bayi' => 'integer|required',
            'resep_obat' => 'string|required',
            'alergi_obat' => 'string|required',
            'riwayat_penyakit' => 'string|required',
            'catatan_khusus' => 'string|required',
            'trisemester_id' => 'integer|required'
        ]);

        DB::beginTransaction();

        $checkupServiceStatement = ServiceStatementIbuHamilPeriksa::where('trisemester_id', $request->trisemester_id)
                                                                    ->where('week', $request->week)->first();

        if(empty($checkupServiceStatement))
            $checkupServiceStatement = new ServiceStatementIbuHamilPeriksa();

        $checkupServiceStatement->week = $request->week;
        $checkupServiceStatement->tanggal_periksa = $request->tanggal_periksa;
        $checkupServiceStatement->tempat_periksa = $request->tempat_periksa;
        $checkupServiceStatement->nama_pemeriksa = $request->nama_pemeriksa;
        $checkupServiceStatement->keluhan_bunda = $request->keluhan_bunda;
        $checkupServiceStatement->tanggal_periksa_kembali = $request->tanggal_periksa_kembali;
        $checkupServiceStatement->bb = $request->bb;
        $checkupServiceStatement->kenaikan_bb = $request->kenaikan_bb;
        $checkupServiceStatement->tb = $request->tb;
        $checkupServiceStatement->tfu = $request->tfu;
        $checkupServiceStatement->djj = $request->djj;
        $checkupServiceStatement->sistolik = $request->sistolik;
        $checkupServiceStatement->diastolik = $request->diastolik;
        $checkupServiceStatement->map = $request->map;
        $checkupServiceStatement->gerakan_bayi = $request->gerakan_bayi;
        $checkupServiceStatement->resep_obat = $request->resep_obat;
        $checkupServiceStatement->alergi_obat = $request->alergi_obat;
        $checkupServiceStatement->riwayat_penyakit = $request->riwayat_penyakit;
        $checkupServiceStatement->catatan_khusus = $request->catatan_khusus;
        $checkupServiceStatement->trisemester_id = $request->trisemester_id;

        if(!empty($request->jenis_kelamin))
            $checkupServiceStatement->jenis_kelamin = $request->jenis_kelamin;
        if(!empty($request->hpl))
            $checkupServiceStatement->hpl = $request->hpl;

        $checkupServiceStatement->save();

        if(!empty($request->img_usg)) {
            $checkupServiceStatement->saveImgUsg($request->img_usg);
            $checkupServiceStatement->save();
        }

        DB::commit();

        return Constants::successResponseWithNewValue('data', [
            'weekly_trisemester_checkup_id' => $checkupServiceStatement->id
        ]);
    }

    public function createUsg(Request $request) {
        $request->validate([
            'weekly_trisemester_checkup_id' => 'integer|required',
            'img_usg' => 'file|required'
        ]);

        $data = ServiceStatementIbuHamilPeriksa::find($request->weekly_trisemester_checkup_id);
        $data->saveImgUsg($request->img_usg);

        return Constants::successResponse();
    }

    public function getWeeklyReport(Request $request) {
        $request->validate([
            'trisemester_id' => 'integer|required',
            'week' => 'integer|required',
        ]);

        $data = ServiceStatementIbuHamilPeriksa::where('trisemester_id', $request->trisemester_id)
                                                ->where('week', $request->week)->first();

        if(empty($data))
            return abort(404);

        return $data;
    }

    public function getWeeklyReportAnalysis(Request $request) {
        $request->validate([
            'weekly_trisemester_checkup_id' => 'integer|required'
        ]);

        $checkupServiceStatement = ServiceStatementIbuHamilPeriksa::find($request->weekly_trisemester_checkup_id);

        if(empty($checkupServiceStatement))
            return Constants::errorResponse('no matching data for weekly_trisemester_checkup_id : ' . $request->weekly_trisemester_checkup_id);

        $weightGrowthParam = WeightGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $momPulseGrowthParam = MomPulseGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $tfuGrowthParam = TfuGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $djjGrowthParam = DjjGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $babyMovementGrowthParam = BabyMovementGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $res['fetus_growth_desc'] = FetusGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $res['weight_desc'] = $this->getBmiDesc(
            $checkupServiceStatement->tb ?? 0,
            $checkupServiceStatement->bb ?? 0,
            $weightGrowthParam->bottom_obesity_threshold ?? 0,
            $weightGrowthParam->bottom_over_threshold ?? 0,
            $weightGrowthParam->bottom_normal_threshold ?? 0,
        );
        $res['mom_pulse_desc'] = $this->getMomPulseDesc($checkupServiceStatement->map, $momPulseGrowthParam->top_threshold ?? 0);
        $res['tfu_desc'] = $this->getTfuDesc($checkupServiceStatement->tfu, $tfuGrowthParam->top_threshold ?? 0, $tfuGrowthParam->bottom_threshold ?? 0);
        $res['djj_desc'] = $this->getDjjDesc($checkupServiceStatement->djj, $djjGrowthParam->top_threshold ?? 0, $djjGrowthParam->bottom_threshold ?? 0);
        $res['baby_movement_desc'] = $this->getBabyMovementDesc($checkupServiceStatement->gerakan_bayi, $babyMovementGrowthParam->bottom_threshold ?? 0);

        return Constants::successResponseWithNewValue('data', $res);
    }

    public function confirmBabyBirth(Request $request) {
        $request->validate([
            'trisemester_id' => 'integer|required'
        ]);

        $trisemester = ServiceStatementIbuHamil::find($request->trisemester_id);
        $anak = $trisemester->kia_anak;
        $anak->is_lahir = true;
        $anak->save();

        return Constants::successResponse();
    }

    public function getImmunizationData() {
        $bunda = Auth::user()->kia_ibu;
        return ServiceStatementIbuImmunization::where('kia_ibu_id', $bunda->id)
                                                ->orderBy('trisemester')->orderBy('immunization_id')->get();
    }

    public function createImmunizationData(Request $request) {
        $request->validate([
            'immunization_id' => 'integer|required',
            'date' => 'date|required',
            'location' => 'string|required',
            'pic' => 'string|required',
        ]);

        $immunization = ServiceStatementIbuImmunization::find($request->immunization_id);
        $immunization->date = $request->date;
        $immunization->location = $request->location;
        $immunization->pic = $request->pic;
        $immunization->save();

        return Constants::successResponse();
    }

    // Graphs

    public function getTfuGraphData($kiaAnakId) {
        $tfuParams = TfuGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('tfu', $kiaAnakId);
        $res = [];
        $currDataIndex = 0;
        $currDataLen = count($insertedData);

        foreach($tfuParams as $tfuParam) {
            $data = [
                'week' => $tfuParam->week,
                'bottom_threshold' => (int) $tfuParam->bottom_threshold,
                'normal_threshold' => (int) $tfuParam->normal_threshold,
                'top_threshold' => (int) $tfuParam->top_threshold,
            ];

            while($currDataIndex < $currDataLen &&
                    $tfuParam->week > $insertedData[$currDataIndex]->week)
                $currDataIndex ++;

            if($currDataIndex < $currDataLen &&
                    $tfuParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = (double) $insertedData[$currDataIndex ++]->tfu;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        $tfuDesc = null;
        try {
            $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
            $age = $this->getPregnancyAgeInWeek($kiaAnak->hpl);
            $paramByAge = TfuGrowthParam::where('week', $age)->first();
            $tfuDesc = $this->getPregnancyGraphDesc('tfu', $age, $kiaAnakId,
                $paramByAge->bottom_threshold,
                $paramByAge->top_threshold,
                'Selamat Bunda! TFU Bunda normal ya, Bun',
                'TFU Bunda tidak normal ya Bun. Bisa konsultasikan ke dokter terdekat ya'
            );
        } catch (\Exception $e) {}

        $resData['data'] = $res;
        $resData['desc'] = $tfuDesc;

        return Constants::successResponseWithNewValue('data', $resData);
    }

    public function getDjjGraphData($kiaAnakId) {
        $djjParams = DjjGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('djj', $kiaAnakId);
        $res = [];
        $currDataIndex = 0;
        $currDataLen = count($insertedData);

        foreach($djjParams as $djjParam) {
            $data = [
                'week' => $djjParam->week,
                'bottom_threshold' => (int) $djjParam->bottom_threshold,
                'top_threshold' => (int) $djjParam->top_threshold,
            ];

            while($currDataIndex < $currDataLen &&
                $djjParam->week > $insertedData[$currDataIndex]->week)
                $currDataIndex ++;

            if($currDataIndex < $currDataLen &&
                    $djjParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = (double) $insertedData[$currDataIndex ++]->djj;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        $djjDesc = null;
        try {
            $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
            $age = $this->getPregnancyAgeInWeek($kiaAnak->hpl);
            $paramByAge = DjjGrowthParam::where('week', $age)->first();
            $djjDesc = $this->getPregnancyGraphDesc('djj', $age, $kiaAnakId,
                $paramByAge->bottom_threshold,
                $paramByAge->top_threshold,
                'Selamat Bunda! Denyut Jantung Janin Bunda normal ya, Bun',
                'Denyut Jantung Janin Bunda kurang ya. Silahkan periksa ke faskes ya Bun'
            );
        } catch (\Exception $e) {}

        $resData['data'] = $res;
        $resData['desc'] = $djjDesc;

        return Constants::successResponseWithNewValue('data', $resData);
    }

    public function getMapGraphData($kiaAnakId) {
        $mapParams = MomPulseGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('map', $kiaAnakId);
        $res = [];
        $currDataIndex = 0;
        $currDataLen = count($insertedData);

        foreach($mapParams as $mapParam) {
            $data = [
                'week' => $mapParam->week,
                'top_threshold' => (int) $mapParam->top_threshold
            ];

            while($currDataIndex < $currDataLen &&
                $mapParam->week > $insertedData[$currDataIndex]->week)
                $currDataIndex ++;

            if($currDataIndex < $currDataLen &&
                    $mapParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = (double) $insertedData[$currDataIndex]->map;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        $mapDesc = null;
        try {
            $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
            $age = $this->getPregnancyAgeInWeek($kiaAnak->hpl);
            $paramByAge = MomPulseGrowthParam::where('week', $age)->first();
            $mapDesc = $this->getPregnancyGraphDesc('map', $age, $kiaAnakId,
                -1,
                $paramByAge->top_threshold,
                'Selamat Bunda! MAP Bunda normal ya, Bun',
                'Bunda beresiko mengalami preeklamsia. Segera menghubungi dokter ya Bun'
            );
        } catch (\Exception $e) {}

        $resData['data'] = $res;
        $resData['desc'] = $mapDesc;

        return Constants::successResponseWithNewValue('data', $resData);
    }

    public function getWeightGraphData($kiaAnakId) {
        $weightParams = WeightGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('bb', $kiaAnakId);
        $res = [];
        $currDataIndex = 0;
        $currDataLen = count($insertedData);

        foreach($weightParams as $weightParam) {
            $data = [
                'week' => $weightParam->week,
                'bottom_obesity_threshold' => (int) $weightParam->bottom_obesity_threshold,
                'bottom_over_threshold' => (int) $weightParam->bottom_over_threshold,
                'bottom_normal_threshold' => (int) $weightParam->bottom_normal_threshold
            ];

            while($currDataIndex < $currDataLen &&
                $weightParam->week > $insertedData[$currDataIndex]->week)
                $currDataIndex ++;

            if($currDataIndex < $currDataLen &&
                    $weightParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = (double) $insertedData[$currDataIndex]->bb;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        $weightDesc = null;
        try {
            $kiaAnak = KiaIdentitasAnak::find($kiaAnakId);
            $age = $this->getPregnancyAgeInWeek($kiaAnak->hpl);
            $paramByAge = WeightGrowthParam::where('week', $age)->first();
            $weightDesc = $this->getPregnancyGraphDesc('bb', $age, $kiaAnakId,
                $paramByAge->bottom_normal_threshold,
                $paramByAge->bottom_over_threshold - 0.1,
                'Selamat Bunda! Berat badan Bunda normal ya, Bun',
                'Berat Bunda tidak normal ya Bun. Bisa konsultasikan ke dokter terdekat ya'
            );
        } catch (\Exception $e) {}

        $resData['data'] = $res;
        $resData['desc'] = $weightDesc;

        return Constants::successResponseWithNewValue('data', $resData);
    }
}
