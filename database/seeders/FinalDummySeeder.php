<?php

namespace Database\Seeders;

use App\Http\Controllers\Mobile\GlobalDataHelper;
use App\Models\CovidForm;
use App\Models\CovidFormAns;
use App\Models\CovidQuestionnaire;
use App\Models\KiaIdentitasAnak;
use App\Models\KiaIdentitasAyah;
use App\Models\KiaIdentitasIbu;
use App\Models\PerkembanganQuestionnaire;
use App\Models\ServiceStatementAnakMonthlyCheckup;
use App\Models\ServiceStatementIbuHamilPeriksa;
use App\Models\ServiceStatementMonthlyPerkembangan;
use App\Models\User;
use App\Utils\Constants;
use App\Utils\Traits\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use League\Csv\Reader;

class FinalDummySeeder extends Seeder
{
    use GlobalDataHelper;
    use Util;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $exists = User::where('email', 'gita@gmail.com')->first();
        if(!empty($exists))
            $this->truncateUserData($exists->id);
        $this->seed_user_data();
        $this->seed_form();
        $this->seed_covid_data();
    }

    private function seed_user_data() {
        $user = User::create([
            'name' => 'Gita Savitra Deva',
            'email' => 'gita@gmail.com',
            'img' => 'https://cdn0-production-images-kly.akamaized.net/2lCN2IHOucR23-xxbX3dKF-_ob4=/640x640/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/1623940/original/078187600_1497434832-1.JPG',
            'password' => Hash::make('password'),
            'user_group_id' => Constants::USER_GROUP_BUNDA
        ]);
        $user->init_notification();

        $bundaData = KiaIdentitasIbu::create([
            'nama' => 'Gita Savitra',
            'img_url' => 'https://cdn0-production-images-kly.akamaized.net/2lCN2IHOucR23-xxbX3dKF-_ob4=/640x640/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/1623940/original/078187600_1497434832-1.JPG',
            'nik' => '3571040504030001',
            'pembiayaan' => 'Bank Syariah',
            'no_jkn' => '0001260979209',
            'faskes_tk1' => 'Klinik Amanah',
            'faskes_rujukan' => 'RS Utama',
            'gol_darah' => 'B',
            'tempat_lahir' => 3578,
            'tanggal_lahir' => '1990-01-01',
            'pendidikan' => 'S1 Kesehatan Masyarakat',
            'pekerjaan' => 'Ahli K3',
            'alamat_rumah' => 'Perumahan Galaxy Permai Blok A-1',
            'telp' => '0838336363',
            'puskesmas_domisili' => 'Puskesmas Suka Indah',
            'nomor_register_kohort_ibu' => '9999883883',
            'user_id' => $user->id
        ]);
        $bundaData->init_fundamental_data();
        KiaIdentitasAyah::create([
            'nama' => 'Putra Negara',
            'img_url' => 'https://cdn0-production-images-kly.akamaized.net/2lCN2IHOucR23-xxbX3dKF-_ob4=/640x640/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/1623940/original/078187600_1497434832-1.JPG',
            'nik' => '3571040504020001',
            'pembiayaan' => 'Bank Syariah',
            'no_jkn' => '0001260979208',
            'faskes_tk1' => 'Klinik Amanah',
            'faskes_rujukan' => 'RS Utama',
            'gol_darah' => 'O',
            'tempat_lahir' => 1101,
            'tanggal_lahir' => '1989-01-01',
            'pendidikan' => 'S1 Hukum',
            'pekerjaan' => 'Hakim',
            'alamat_rumah' => 'Peru',
            'telp' => '0838336363',
            'kia_ibu_id' => $bundaData->id
        ]);

        $anakData = new KiaIdentitasAnak();
        $anakData->nama = 'Putra';
        $anakData->img_url = 'https://cdn0-production-images-kly.akamaized.net/2lCN2IHOucR23-xxbX3dKF-_ob4=/640x640/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/1623940/original/078187600_1497434832-1.JPG';
        $anakData->anak_ke = 1;
        $anakData->no_akte_kelahiran = '3578-LU-28112012-0018';
        $anakData->nik = '3571040504020001';
        $anakData->jenis_kelamin = 'L';
        $anakData->gol_darah = 'B';
        $anakData->tempat_lahir = 3578;
        $anakData->tanggal_lahir = '2018-01-01';
        $anakData->no_jkn = '3589292';
        $anakData->tanggal_berlaku_jkn = '2020-01-01';
        $anakData->no_kohort = '23973739389';
        $anakData->no_catatan_medik = 'md/239/2018';
        $anakData->is_janin = false;
        $anakData->kia_ibu_id = $bundaData->id;
        $anakData->save();
        $anakData->init_fundamental_data();

        $anakData = new KiaIdentitasAnak();
        $anakData->nama = 'Putri';
        $anakData->img_url = 'https://cdn0-production-images-kly.akamaized.net/2lCN2IHOucR23-xxbX3dKF-_ob4=/640x640/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/1623940/original/078187600_1497434832-1.JPG';
        $anakData->anak_ke = 2;
        $anakData->no_akte_kelahiran = '3578-LU-28112012-0020';
        $anakData->nik = '2239838837';
        $anakData->jenis_kelamin = 'P';
        $anakData->gol_darah = 'B';
        $anakData->tempat_lahir = 1101;
        $anakData->tanggal_lahir = '2020-01-01';
        $anakData->no_jkn = 'No JKn';
        $anakData->tanggal_berlaku_jkn = '2020-01-01';
        $anakData->no_kohort = '23973739389';
        $anakData->no_catatan_medik = 'md/239/2020';
        $anakData->is_janin = false;
        $anakData->kia_ibu_id = $bundaData->id;
        $anakData->save();
        $anakData->init_fundamental_data();

        $janin = new KiaIdentitasAnak();
        $janin->img_url = 'https://cdn0-production-images-kly.akamaized.net/2lCN2IHOucR23-xxbX3dKF-_ob4=/640x640/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/1623940/original/078187600_1497434832-1.JPG';
        $janin->nama = 'Janin 1';
        $janin->hpl = Carbon::now()->addWeeks(40);
        $janin->kia_ibu_id = $bundaData->id;
        $janin->is_lahir = false;
        $janin->save();
        $janin->init_fundamental_data();
    }

    private function seed_form() {
        $anak = User::where('email', 'gita@gmail.com')->first()->kia_ibu->kia_anak;

        foreach($anak as $a) {
            if($a->is_janin) {
                $this->seed_kehamilan_data($a);
            } else if($a->jenis_kelamin == 'L') {
                $this->seed_bayiku_data($a, 'dumm_bayi.csv');
            } else {
                $this->seed_bayiku_data($a, 'dumm_bayi_p.csv');
            }
        }
    }

    private function seed_kehamilan_data($anak) {
        $data = Reader::createFromPath(database_path('seeders/csv/dumm_kehamilan.csv'), 'r');
        $data->setDelimiter(',');
        $data->setHeaderOffset(0);

        foreach($data as $d) {
            if($d['week'] >= 1 && $d['week']<= 12) {
                $trisemester = 1;
            } else if($d['week'] >= 13 && $d['week']<= 24) {
                $trisemester = 2;
            } else {
                $trisemester = 3;
            }

            if($d['week'] > 35)
                break;

            $trisemester_id = 1;
            foreach($anak->trisemesters as $t) {
                if($trisemester == $t->trisemester) {
                    $trisemester_id = $t->id;
                    break;
                }
            }

            $date = Carbon::now()->addWeeks($d['week']);
            echo 'week ' . $d['week'] . ' : ';
            if(!empty($d['bb']) || !empty($d['tfu']) || !empty($d['djj']) || !empty($d['mom_pulse'])) {
                $newData = new ServiceStatementIbuHamilPeriksa();
                $newData->week = $d['week'];
                $newData->tanggal_periksa = $date;
                $newData->tanggal_periksa_kembali = $date->addWeek();
                $newData->tempat_periksa = "Klinik Damai";
                $newData->nama_pemeriksa = "dr Amir";
                $newData->keluhan_bunda = "Tidak ada";
                $newData->jenis_kelamin = 'L';
                $newData->hpl = $anak->hpl;
                $newData->bb = $this->nullableVal($d['bb']) ?? 0.0;
                $newData->tfu = $this->nullableVal($d['tfu']) ?? 0.0;
                $newData->djj = $this->nullableVal($d['djj']) ?? 0.0;
                $newData->map = $this->nullableVal($d['mom_pulse']) ?? 0.0;
                $newData->kenaikan_bb = $this->nullableVal($d['kenaikan_bb']) ?? 0.0;
                $newData->tb = $this->nullableVal($d['tb']) ?? 0.0;
                $newData->sistolik = 90;
                $newData->diastolik = 60;
                $newData->gerakan_bayi = rand(7, 15);
                $newData->resep_obat = "-";
                $newData->alergi_obat = "-";
                $newData->riwayat_penyakit = "-";
                $newData->catatan_khusus = "-";
                $newData->trisemester_id = $trisemester_id;
                $newData->save();
                echo 'data found' . PHP_EOL;
            } else {
                echo 'data not found' . PHP_EOL;
            }
        }
    }

    private function seed_bayiku_data($anak, $fileName) {
        $dataAnakLaki = Reader::createFromPath(database_path('seeders/csv/' . $fileName), 'r');
        $dataAnakLaki->setDelimiter(',');
        $dataAnakLaki->setHeaderOffset(0);
        foreach($dataAnakLaki as $d) {
            if($d['month']<= 12) {
                $year = 1;
            } else if($d['month'] >= 13 && $d['month']<= 24) {
                $year = 2;
            } else if($d['month'] >= 25 && $d['month']<= 36) {
                $year = 3;
            } else if($d['month'] >= 37 && $d['month']<= 48) {
                $year = 4;
            } else
                break;/* else if($d['month'] >= 49 && $d['month']<= 60) {
                $year = 5;
            } else if($d['month'] >= 61) {
                $year = 6;
            }*/

            $year_id = 1;
            foreach($anak->years as $y) {
                if($year == $y->year) {
                    $year_id = $y->id;
                    break;
                }
            }

            $date = Carbon::now()->addMonths($d['month']);
            echo 'month ' . $d['month'] . ' : ';
            if(!empty($d['bb']) && !empty($d['pb']) && !empty($d['lingkar_kepala']) && !empty($d['imt'])) {
                $newData = new ServiceStatementAnakMonthlyCheckup();
                $newData->month = $d['month'];
                $newData->date = $date;
                $newData->location = 'RS Utama';
                $newData->pemeriksa = 'Dokter Amir';
                $newData->age = $d['month'] * 30;
                $newData->bb = $this->nullableVal($d['bb']) ?? 0.0;
                $newData->tb = $this->nullableVal($d['pb']) ?? 0.0;
                $newData->lingkar_kepala = $this->nullableVal($d['lingkar_kepala']) ?? 0.0;
                $newData->imt = $this->nullableVal($d['imt']) ?? 0.0;
                $newData->year_id = $year_id;
                $newData->save();

                // pertanyaan perkembangan
                $questions = PerkembanganQuestionnaire::where('month_start', '<=', $d['month'])
                    ->where('month_until', '>=', $d['month'])
                    ->orderBy('no')->get();

                $yesCount = rand(5, 10);

                foreach($questions as $q) {
                    if($yesCount > 0) {
                        $yes = rand(0, 1);
                        if($yes)
                            $yesCount --;
                    }
                    ServiceStatementMonthlyPerkembangan::create([
                        'monthly_report_id' => $newData->id,
                        'questionnaire_id' => $q->id,
                        'ans' => $yes
                    ]);
                }

                echo 'data found ' . PHP_EOL;
            } else
                echo 'data not found' . PHP_EOL;
        }
    }

    private function seed_covid_data() {
        // anak
        $anak = User::where('email', 'gita@gmail.com')->first()->kia_ibu->kia_anak;
        $questions = CovidQuestionnaire::all();
        foreach($anak as $a) {
            if($a->is_janin)
                continue;

            $this->createCovidForm($questions, false, $a->id);
        }

        // ibu
        $this->createCovidForm($questions, true, null);
    }

    private function createCovidForm($questions, $is_ibu, $anakId) {
        $user = User::where('email', 'gita@gmail.com')->first();

        $covidForm = CovidForm::create([
            'is_ibu' => $is_ibu,
            'date' => Carbon::now(),
            'user_id' => $user->id
        ]);

        foreach ($questions as $q) {
            CovidFormAns::create([
                'q_id' => $q->id,
                'form_id' => $covidForm->id,
                'ans' => rand(0, 1)
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
            $covidForm->result_desc = 'Terindikasi PDP';
            $covidForm->result_long_desc = 'Bunda, dari hasil form, ' .
                (!$is_ibu ? 'bayi ' : '') . 'bunda masuk kategori Pasien Dalam Pengawasan';
            $covidForm->result_is_normal = false;
        } else if($isOdp) {
            $covidForm->result_is_normal = false;
            $covidForm->result_desc = 'Terindikasi ODP';
            $covidForm->result_long_desc = 'Bunda, dari hasil form, ' .
                (!$is_ibu ? 'bayi ' : '') . 'bunda masuk kategori Orang Dalam Pengawasan';
            $covidForm->result_is_normal = false;
        } else {
            $covidForm->result_is_normal = false;
            $covidForm->result_desc = 'Terindikasi Normal';
            $covidForm->result_long_desc = 'Bunda, dari hasil form, ' .
                (!$is_ibu ? 'bayi ' : '') . 'bunda masuk kategori Normal';
            $covidForm->result_is_normal = true;
        }

        if(!$q->is_ibu) {
            $covidForm->kia_anak_id = $anakId;
            $covidForm->img_url = 'https://sibunda.amirmb.com/res/img/covid/result_anak.png';
        } else
            $covidForm->img_url = 'https://sibunda.amirmb.com/res/img/covid/result_ibu.png';

        $covidForm->save();
    }
}
