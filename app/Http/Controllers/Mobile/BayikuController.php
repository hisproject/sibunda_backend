<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\KiaIdentitasAnak;
use App\Models\PerkembanganQuestionnaire;
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
            $anak = KiaIdentitasAnak::select('id', 'nama', 'anak_ke')->with('years')
                ->where('kia_ibu_id', $kiaIbu->id)->where('is_janin', false)->get();
            foreach ($anak as $a) {
                $anak->age = $this->getChildAge($a->tanggal_lahir ?? null);
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
}
