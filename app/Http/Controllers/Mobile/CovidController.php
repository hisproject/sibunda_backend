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

            if($isPdp) {
                $covidForm->result_is_normal = false;
                $covidForm->result_desc = 'PDP';
            } else if($isOdp) {
                $covidForm->result_is_normal = false;
                $covidForm->result_desc = 'ODP';
            } else {
                $covidForm->result_is_normal = false;
                $covidForm->result_desc = 'Normal';
            }

            if(!$request->is_ibu)
                $covidForm->kia_anak_id = $request->anak_id;

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
}
