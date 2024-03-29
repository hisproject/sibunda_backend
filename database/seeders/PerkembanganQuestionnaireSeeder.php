<?php

namespace Database\Seeders;

use App\Models\PerkembanganQuestionnaire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PerkembanganQuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = Reader::createFromPath(database_path('seeders/csv/perkembangan_questionnaire.csv'), 'r');
        $data->setDelimiter(',');
        $data->setHeaderOffset(0);

        PerkembanganQuestionnaire::query()->truncate();

        DB::statement('ALTER SEQUENCE perkembangan_questionnaire_id_seq RESTART 1');

        foreach($data as $d) {
            $newData = PerkembanganQuestionnaire::create([
                'no' => $d['no'],
                'question' => $d['question'],
                'month_start' => $d['month_start'],
                'month_until' => $d['month_until']
            ]);

            if(!empty($d['img'])) {
                $newData->img_url = 'https://sibunda.amirmb.com' . $d['img'];
                $newData->save();
            }
        }
    }
}
