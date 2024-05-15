<?php

namespace Database\Seeders;

use App\Models\AnakParamImt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class AnakParamImtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        AnakParamImt::query()->truncate();
        // enable this if, you're using Postgres
        // DB::statement('ALTER SEQUENCE anak_param_imt_id_seq RESTART 1');

        $data = Reader::createFromPath(database_path('seeders/csv/anak_param_imt.csv'), 'r');
        $data->setDelimiter(',');
        $data->setHeaderOffset(0);

        foreach($data as $d) {
            AnakParamImt::create([
                'is_laki' => $d['is_laki'],
                'month' => $d['month'],
                'minus_3_sd' => $d['minus_3_sd'],
                'minus_2_sd' => $d['minus_2_sd'],
                'minus_1_sd' => $d['minus_1_sd'],
                'median' => $d['median'],
                'plus_1_sd' => $d['plus_1_sd'],
                'plus_2_sd' => $d['plus_2_sd'],
                'plus_3_sd' => $d['plus_3_sd']
            ]);
        }
    }
}
