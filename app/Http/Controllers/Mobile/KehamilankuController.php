<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BabyMovementGrowthParam;
use App\Models\DjjGrowthParam;
use App\Models\FetusGrowthParam;
use App\Models\KiaIdentitasIbu;
use App\Models\MomPulseGrowthParam;
use App\Models\ServiceStatementIbuHamilPeriksa;
use App\Models\ServiceStatementIbuImmunization;
use App\Models\TfuGrowthParam;
use App\Models\WeightGrowthParam;
use App\Utils\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class KehamilankuController extends Controller
{
    use GlobalDataHelper;
    //
    public function getAnak() {

    }
    public function getOverview() {
        $res = new stdClass();
        $kiaAnak = Auth::user()->kia_ibu->kia_anak;
        foreach($kiaAnak as $ka) {
            $ka->week = $this->getPregnancyAgeInWeek($ka->hpl);
            $ka->remaining_days = $this->getRemainingPregnancyDays($ka->hpl);
        }

        // cek test rekomendasi
        $res->food_recommendations = [
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
        $res->children = $kiaAnak;

        return Constants::successResponseWithNewValue('data', $res);
    }

    public function createTriSemesterData(Request $request) {
        $data = $request->validate([
            'week' => 'integer|required',
            'tanggal_periksa' => 'date|required',
            'tempat_periksa' => 'date|required',
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

    public function showTriSemesterCheckupData(Request $request) {
        $request->validate([
            'weekly_trisemester_checkup_id' => 'integer|required'
        ]);

        $checkupServiceStatement = ServiceStatementIbuHamilPeriksa::find($request->weekly_trisemester_checkup_id);
        $weightGrowthParam = WeightGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $momPulseGrowthParam = MomPulseGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $tfuGrowthParam = TfuGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $djjGrowthParam = DjjGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $babyMovementGrowthParam = BabyMovementGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $res['fetus_growth_desc'] = FetusGrowthParam::where('week', $checkupServiceStatement->week)->first();
        $res['weight_desc'] = $this->getBmiDesc(
            $checkupServiceStatement->tb,
            $checkupServiceStatement->bb,
            $weightGrowthParam->bottom_obesity_threshold,
            $weightGrowthParam->bottom_over_threshold,
            $weightGrowthParam->bottom_normal_threshold,
        );
        $res['mom_pulse_desc'] = $this->getMomPulseDesc($checkupServiceStatement->map, $momPulseGrowthParam->top_threshold);
        $res['tfu_desc'] = $this->getTfuDesc($checkupServiceStatement->tfu, $tfuGrowthParam->top_threshold, $tfuGrowthParam->bottom_threshold);
        $res['djj_desc'] = $this->getDjjDesc($checkupServiceStatement->djj, $djjGrowthParam->top_threshold, $djjGrowthParam->bottom_threshold);
        $res['baby_movement_desc'] = $this->getBabyMovementDesc($checkupServiceStatement->gerakan_bayi, $babyMovementGrowthParam->bottom_threshold);

        return Constants::successResponseWithNewValue('data', $res);
    }

    public function getImmunizationData($bundaId) {
        $bunda = KiaIdentitasIbu::find($bundaId);

        return $bunda->immunization();
    }

    public function createImmunizationData(Request $request) {
        $request->validate([
            'immunization_id' => 'integer|required',
            'date' => 'date|required',
            'place' => 'string|required',
            'pic' => 'string|required',
        ]);

        $immunization = ServiceStatementIbuImmunization::find($request->immunization_id);
        $immunization->date = $request->date;
        $immunization->place = $request->place;
        $immunization->pic = $request->pic;
        $immunization->save();

        return Constants::successResponse();
    }
}
