<?php

namespace Database\Seeders;

use App\Models\PerkembanganQuestionnaire;
use Illuminate\Database\Seeder;
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

        foreach($data as $d) {
            $newData = PerkembanganQuestionnaire::create([
                'question' => $d['question'],
                'month_start' => $d['month_start'],
                'month_until' => $d['month_until']
            ]);

            if(!empty($d['img_url'])) {
                $newData->img_url = $d['img_url'];
                $newData->save();
            }
        }
    }
}
