<?php
namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * by Amir Mu'tashim Billah
     * @return void
     */
    public function run()
    {
        //
        $basePath = 'seeders/csv/';

        Provinsi::query()->truncate();
        Kota::query()->truncate();
        Kecamatan::query()->truncate();
        Kelurahan::query()->truncate();

        $this->importDataFromCsv(Provinsi::class, $basePath.'provinces.csv');
        $this->importDataFromCsv(Kota::class, $basePath.'regencies.csv', 'provinsi_id');
        $this->importDataFromCsv(Kecamatan::class, $basePath.'districts.csv', 'kota_id');
        $this->importDataFromCsv(Kelurahan::class, $basePath.'villages.csv', 'kecamatan_id');

    }

    private function importDataFromCsv($model, $fileName, $foreignKey = null) {
        $records = Reader::createFromPath(database_path($fileName), 'r');
        $records->setDelimiter(',');
        $records->setHeaderOffset(0);

        foreach ($records as $record) {
            if ($foreignKey == null) {
                $model::create([
                    'id' => $record['id'],
                    'nama' => $record['name']
                ]);
            } else {
                $model::create([
                    'id' => $record['id'],
                    'nama' => $record['name'],
                    $foreignKey => $record['foreign']
                ]);
            }
        }
    }
}
