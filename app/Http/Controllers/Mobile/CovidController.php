<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\CovidForm;
use App\Models\CovidFormAns;
use App\Models\CovidQuestionnaire;
use App\Utils\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CovidController extends Controller
{
    //
    use GlobalDataHelper;

    public function createCovidCheckup(Request $request) {
        $request->validate([
            'is_ibu' => 'boolean|required',
            'date' => 'date|required',
            'answers' => 'array|required',
            'answers.*.q_id' => 'integer|required',
            'answers.*.ans' => 'integer|required',
            'anak_id' => 'integer'
        ]);

        DB::beginTransaction();
        try {
            $covidForm = CovidForm::create([
                'is_ibu' => $request->is_ibu,
                'date' => $request->date,
                'user_id' => Auth::id()
            ]);

            foreach ($request->answers as $answer) {
                CovidFormAns::create([
                    'q_id' => $answer['q_id'],
                    'form_id' => $covidForm->id,
                    'ans' => $answer['ans']
                ]);
            }

            $pdp = [
                [1, 2, 3, 4, 5],
                [1, 2, 4, 5],
                [1, 2, 3, 4, 6],
                [1, 2, 4, 6],
                [1, 7],
                [1, 2, 3, 7],
                [1, 2, 7]
            ];
            $odp = [
                [1, 4, 5],
                [2, 4, 5],
                [1, 4, 6],
                [2, 4, 6],
                [2, 7]
            ];
            $suspect = [[1, 2, 3, 4, 5, 6, 7]];

            $isPdp = false;
            foreach($pdp as $ids) {
                if($this->meetCovidCategory($covidForm->id, $ids)) {
                    $isPdp = true;
                    break;
                }
            }

            $isOdp = false;
            if(!$isPdp) {
                foreach($odp as $ids) {
                    if($this->meetCovidCategory($covidForm->id, $ids)) {
                        $isOdp = true;
                        break;
                    }
                }
            }

            $isSuspect = false;
            if(!$isOdp) {
                foreach($suspect as $ids) {
                    if($this->meetCovidCategory($covidForm->id, $ids)) {
                        $isSuspect = true;
                        break;
                    }
                }
            }

            if($isPdp) {
                $covidForm->result_is_normal = false;
                $covidForm->result_desc = 'Terindikasi PDP';
                $covidForm->result_long_desc = 'Bunda, dari hasil form, ' .
                    (!$request->is_ibu ? 'bayi ' : '') . 'bunda masuk kategori Pasien Dalam Pengawasan';
                $covidForm->result_is_normal = false;
            } else if($isOdp) {
                $covidForm->result_is_normal = false;
                $covidForm->result_desc = 'Terindikasi ODP';
                $covidForm->result_long_desc = 'Bunda, dari hasil form, ' .
                    (!$request->is_ibu ? 'bayi ' : '') . 'bunda masuk kategori Orang Dalam Pengawasan';
                $covidForm->result_is_normal = false;
            } else if($isSuspect) {
                $covidForm->result_is_normal = false;
                $covidForm->result_desc = 'Terindikasi Suspect COVID-19';
                $covidForm->result_long_desc = 'Bunda, dari hasil form, ' .
                    (!$request->is_ibu ? 'bayi ' : '') . 'bunda masuk kategori Suspect COVID-19';
                $covidForm->result_is_normal = false;
            } else {
                $covidForm->result_is_normal = false;
                $covidForm->result_desc = 'Terindikasi Normal';
                $covidForm->result_long_desc = 'Bunda, dari hasil form, ' .
                    (!$request->is_ibu ? 'bayi ' : '') . 'bunda masuk kategori Normal';
                $covidForm->result_is_normal = true;
            }

            if(!$request->is_ibu) {
                $covidForm->kia_anak_id = $request->anak_id;
                $covidForm->img_url = 'https://sibunda.amirmb.com/res/img/covid/result_anak.png';
            } else
                $covidForm->img_url = 'https://sibunda.amirmb.com/res/img/covid/result_ibu.png';

            $covidForm->save();

            DB::commit();
            return Constants::successResponseWithNewValue('data', $covidForm);
        } catch (\Exception $e) {
            DB::rollBack();
            return Constants::errorResponse();
        }
    }

    public function getCovidCheckup() {
        $data = CovidForm::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return Constants::successResponseWithNewValue('data', $data);
    }

    public function getQuestionnaire() {
        $data = CovidQuestionnaire::orderBy('id')->get();

        return Constants::successResponseWithNewValue('data', $data);
    }

    public function getAnak() {
        return Constants::successResponseWithNewValue('data', Auth::user()->kia_ibu->kia_anak);
    }
}
