<?php

namespace Database\Seeders;

use App\Models\BabyMovementGrowthParam;
use App\Models\DjjGrowthParam;
use App\Models\FetusGrowthParam;
use App\Models\Immunization;
use App\Models\MomPulseGrowthParam;
use App\Models\TfuGrowthParam;
use App\Models\WeightGrowthParam;
use App\Utils\Traits\Util;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class ParameterSeeder extends Seeder {
    use Util;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        FetusGrowthParam::query()->truncate();
        WeightGrowthParam::query()->truncate();
        TfuGrowthParam::query()->truncate();
        DjjGrowthParam::query()->truncate();
        MomPulseGrowthParam::query()->truncate();
        BabyMovementGrowthParam::query()->truncate();
        Immunization::query()->truncate();

        $fetusGrowths = Reader::createFromPath(database_path('seeders/csv/fetus_growths.csv'), 'r');
        $fetusGrowths->setDelimiter(',');
        $fetusGrowths->setHeaderOffset(0);
        $weightGrowths = Reader::createFromPath(database_path('seeders/csv/weight_growths.csv'), 'r');
        $weightGrowths->setDelimiter(',');
        $weightGrowths->setHeaderOffset(0);
        $tfuGrowths = Reader::createFromPath(database_path('seeders/csv/tfu_growths.csv'), 'r');
        $tfuGrowths->setDelimiter(',');
        $tfuGrowths->setHeaderOffset(0);
        $djjGrowths = Reader::createFromPath(database_path('seeders/csv/djj_growths.csv'), 'r');
        $djjGrowths->setDelimiter(',');
        $djjGrowths->setHeaderOffset(0);
        $momPulseGrowths = Reader::createFromPath(database_path('seeders/csv/mom_pulse_growths.csv'), 'r');
        $momPulseGrowths->setDelimiter(',');
        $momPulseGrowths->setHeaderOffset(0);


        // insert data
        foreach ($fetusGrowths as $d) {
            echo $d['week'] . PHP_EOL;
            echo $d['length'] . PHP_EOL;
            echo $d['weight'] . PHP_EOL;
            echo $d['size'] . PHP_EOL;
            echo $d['icon'] . PHP_EOL;
            FetusGrowthParam::create([
                'week' => $d['week'],
                'length' => $this->nullableVal($d['length'], '-'),
                'weight' => $this->nullableVal($d['weight'], '-'),
                'desc' => $this->nullableVal($d['size'], '-'),
                'img' => $this->nullableVal($d['icon'], '-')
            ]);
        }

        foreach ($weightGrowths as $d) {
            WeightGrowthParam::create([
                'week' => $d['week'],
                'bottom_obesity_threshold' => $d['bottom_obesity_threshold'],
                'bottom_over_threshold' => $d['bottom_over_threshold'],
                'bottom_normal_threshold' => $d['bottom_normal_threshold']
            ]);
        }

        foreach ($tfuGrowths as $d) {
            TfuGrowthParam::create([
                'week' => $d['week'],
                'bottom_threshold' => $d['bottom_threshold'],
                'normal_threshold' => $d['normal_threshold'],
                'top_threshold' => $d['top_threshold']
            ]);
        }

        foreach ($djjGrowths as $d) {
            DjjGrowthParam::create([
                'week' => $d['week'],
                'bottom_threshold' => $d['bottom_threshold'],
                'top_threshold' => $d['top_threshold']
            ]);
        }

        foreach ($momPulseGrowths as $d) {
            MomPulseGrowthParam::create([
                'week' => $d['week'],
                'top_threshold' => $d['top_threshold']
            ]);
        }

        for ($i = 1; $i <= 40; $i++) {
            BabyMovementGrowthParam::create([
                'week' => $i,
                'top_threshold' => 12,
                'bottom_threshold' => 10
            ]);
        }

        $immunizations = [
            'Tetanus',
            'Hepatitis B (<24 Jam)',
            'BCG',
            'Polio Tetes 1',
            'DPT-HB-Hib 1',
            'Polio Tetes 2',
            'DPT-HB-Hib 2',
            'Polio Tetes 3',
            'DPT-HB-Hib 3',
            'Polio Tetes 4',
            'Polio Suntik (IPV)',
            '*PCV 1',
            '*PCV 2',
            'Campak - Rubella (MR)',
            'DPT-Hib-HB lanjutan',
            'Campak - Rubella (MR)',
            '*Japanese Encephalitis',
            '*PCV 3'
        ];

        foreach($immunizations as $immunization)
            Immunization::create([
                'name' => $immunization
            ]);
    }
}
