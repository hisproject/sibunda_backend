<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BabyMovementGrowthParam;
use App\Models\DjjGrowthParam;
use App\Models\FetusGrowthParam;
use App\Models\KiaIdentitasAnak;
use App\Models\KiaIdentitasIbu;
use App\Models\MomPulseGrowthParam;
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
        $janin = KiaIdentitasAnak::select('id', 'nama', 'anak_ke')->with('trisemesters')
                            ->where('kia_ibu_id', $kiaIbu->id)->where('is_janin', true)->get();
        foreach($janin as $j) {
            $j->week = $this->getPregnancyAgeInWeek($j->hpl);
            $j->remaining_days = $this->getRemainingPregnancyDays($j->hpl);
            // cek test rekomendasi
            $j->food_recommendations = [
                [
                    'food_category' => 'Nasi atau Makanan Pokok',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 5 porsi nasi ya, dengan 1 porsi = 100 gr atau 3/4 gelas nasi',
                ],
                [
                    'food_category' => 'Nasi atau Makanan Pokok',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 5 porsi nasi ya, dengan 1 porsi = 100 gr atau 3/4 gelas nasi',
                ],
                [
                    'food_category' => 'Nasi atau Makanan Pokok',
                    'food_dose' => 'Bunda, 1 hari minimal harus makan 5 porsi nasi ya, dengan 1 porsi = 100 gr atau 3/4 gelas nasi',
                ]
            ];
        }

        return Constants::successResponseWithNewValue('data', $janin);
    }

    public function createTriSemesterData(Request $request) {
        $data = $request->validate([
            'week' => 'integer|required',
            'tanggal_periksa' => 'date|required',
            'tempat_periksa' => 'string|required',
            'nama_pemeriksa' => 'string|required',
            'keluhan_bunda' => 'string|required',
            'jenis_kelamin' => 'size:1',
            'tanggal_periksa_kembali' => 'date|required',
            'hpl' => 'date|required',
            'bb' => 'numeric',
            'kenaikan_bb' => 'numeric',
            'tb' => 'numeric',
            'tfu' => 'numeric',
            'djj' => 'numeric',
            'sistolik' => 'numeric',
            'diastolik' => 'numeric',
            'map' => 'numeric',
            'gerakan_bayi' => 'integer',
            'resep_obat' => 'string',
            'alergi_obat' => 'string',
            'riwayat_penyakit' => 'string',
            'catatan_khusus' => 'string',
            'trisemester_id' => 'integer'
        ]);

        // file
        if(!empty($request->usg)) {

        }

        $checkupServiceStatement = ServiceStatementIbuHamilPeriksa::create($data);

        return Constants::successResponseWithNewValue('data', [
            'weekly_trisemester_checkup_id' => $checkupServiceStatement->id
        ]);
    }

    public function showWeeklyTrisemesterData(Request $request) {
        $request->validate([
            'weekly_trisemester_checkup_id' => 'integer|required'
        ]);

        $data = ServiceStatementIbuHamilPeriksa::find($request->weekly_trisemester_checkup_id);

        if(empty($data))
            return abort(404);

        return $data;
    }

    public function showTriSemesterAnalysis(Request $request) {
        $request->validate([
            'weekly_trisemester_checkup_id' => 'integer|required'
        ]);

        $checkupServiceStatement = ServiceStatementIbuHamilPeriksa::find($request->weekly_trisemester_checkup_id);

        if(empty($checkupServiceStatement))
            return abort(404);

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

    public function getTfuGraphData() {
        $tfuParams = TfuGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('tfu');
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

            if($currDataIndex < $currDataLen &&
                    $tfuParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = $insertedData[$currDataIndex ++]->tfu;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        return $res;
    }

    public function getDjjGraphData() {
        $djjParams = DjjGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('djj');
        $res = [];
        $currDataIndex = 0;
        $currDataLen = count($insertedData);

        foreach($djjParams as $djjParam) {
            $data = [
                'week' => $djjParam->week,
                'bottom_threshold' => (int) $djjParam->bottom_threshold,
                'top_threshold' => (int) $djjParam->top_threshold,
            ];

            if($currDataIndex < $currDataLen &&
                    $djjParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = $insertedData[$currDataIndex ++]->djj;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        return $res;
    }

    public function getMapGraphData() {
        $mapParams = MomPulseGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('map');
        $res = [];
        $currDataIndex = 0;
        $currDataLen = count($insertedData);

        foreach($mapParams as $mapParam) {
            $data = [
                'week' => $mapParam->week,
                'top_threshold' => (int) $mapParam->top_threshold
            ];

            if($currDataIndex < $currDataLen &&
                    $mapParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = $insertedData[$currDataIndex]->map;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        return $res;
    }

    public function getWeightGraphData() {
        $weightParams = WeightGrowthParam::orderBy('week')->get();
        $insertedData = $this->getPregnancyData('bb');
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

            if($currDataIndex < $currDataLen &&
                    $weightParam->week == $insertedData[$currDataIndex]->week)
                $data['input'] = $insertedData[$currDataIndex]->bb;
            else
                $data['input'] = -1;

            array_push($res, $data);
        }

        return $res;
    }
}
