<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\KiaIdentitasAnak;
use App\Models\ServiceStatementAnakMonthlyCheckup;
use App\Models\ServiceStatementAnakNeonatusKn1;
use App\Models\ServiceStatementAnakNeonatusKn2;
use App\Models\ServiceStatementAnakNeonatusKn3;
use App\Models\ServiceStatementAnakNeonatusSixHours;
use App\Utils\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// bayiku/anak
class BayikuController extends Controller
{
    use GlobalDataHelper;
    //
    public function getOverview() {
        $kiaIbu = Auth::user()->kia_ibu;
        $anak = KiaIdentitasAnak::select('id', 'nama', 'anak_ke')->with('trisemesters')
            ->where('kia_ibu_id', $kiaIbu->id)->where('is_janin', false)->get();
        foreach($anak as $a) {
            $anak->age = $this->getChildAge($a->tanggal_lahir ?? null);
        }

        return Constants::successResponseWithNewValue('data', $anak);
    }

    public function createMonthlyCheckup(Request $request) {
        $request->validate([
            'year_id' => 'integer|required',
            'date' => 'date',
            'location' => 'string',
            'age' => 'integer',
            'bb' => 'numeric',
            'tb' => 'numeric',
            'lingkar_kepala' => 'numeric',
            'imt' => 'numeric',
            'perkembangan' => 'array|required'
        ]);

        DB::beginTransaction();
        try {
            $checkupData = new ServiceStatementAnakMonthlyCheckup();
            $checkupData->year_id = $request->year_id;
            $checkupData->date = $request->date;
            $checkupData->location = $request->location;
            $checkupData->age = $request->age;
            $checkupData->bb = $request->bb;
            $checkupData->tb = $request->tb;
            $checkupData->lingkar_kepala = $request->lingkar_kepala;
            $checkupData->imt = $request->imt;
            $checkupData->save();

            // key harus 1 - indexed
            $checkupData->fill_perkembangan_qs($request->perkembangan);

            DB::commit();
            return Constants::successResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return Constants::errorResponse($e->getMessage());
        }
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
            'time' => 'time|required',
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
            'time' => 'time|required',
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
            'time' => 'time|required',
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
