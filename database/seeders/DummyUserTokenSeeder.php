<?php

namespace Database\Seeders;

use App\Models\KiaIdentitasAnak;
use App\Models\KiaIdentitasAyah;
use App\Models\KiaIdentitasIbu;
use App\Models\User;
use App\Utils\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyUserTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => 'Ibunda',
                'email' => 'a@a.a',
                'password' => Hash::make('password'),
                'user_group_id' => Constants::USER_GROUP_BUNDA
            ]);

            $bundaData = KiaIdentitasIbu::create([
                'nama' => 'Ibunda',
                'nik' => '23598359835',
                'pembiayaan' => 'Pembiayaan',
                'no_jkn' => 'No JKN',
                'faskes_tk1' => 'Faskes TK1',
                'faskes_rujukan' => 'Faskes Rujukan',
                'gol_darah' => 'B',
                'tempat_lahir' => 1101,
                'tanggal_lahir' => '2000-01-01',
                'pendidikan' => 'Pendidikan',
                'pekerjaan' => 'Pekerjaan',
                'alamat_rumah' => 'Alamat Rumah',
                'telp' => '0838336363',
                'puskesmas_domisili' => 'Puskesmas Domisili',
                'nomor_register_kohort_ibu' => 'No Kohor',
                'user_id' => $user->id
            ]);
            $bundaData->init_fundamental_data();
            KiaIdentitasAyah::create([
                'nama' => 'Ayah',
                'nik' => '0238539539',
                'pembiayaan' => 'Pembiayaan',
                'no_jkn' => 'no jkn',
                'faskes_tk1' => 'faskes tk1',
                'faskes_rujukan' => 'faskes rujukan',
                'gol_darah' => 'O',
                'tempat_lahir' => 1101,
                'tanggal_lahir' => '2002-01-01',
                'pendidikan' => 'Pendidikan',
                'pekerjaan' => 'Pekerjaan',
                'alamat_rumah' => 'Alamat Rumah',
                'telp' => 'Telp',
                'kia_ibu_id' => $bundaData->id
            ]);

            $anakData = new KiaIdentitasAnak();
            $anakData->nama = 'Anak';
            $anakData->anak_ke = 1;
            $anakData->no_akte_kelahiran = '2398383';
            $anakData->nik = '2239838837';
            $anakData->gol_darah = 'B';
            $anakData->tempat_lahir = 1101;
            $anakData->tanggal_lahir = '2018-01-01';
            $anakData->no_jkn = 'No JKn';
            $anakData->tanggal_berlaku_jkn = '2020-01-01';
            $anakData->no_kohort = '23973739389';
            $anakData->no_catatan_medik = 'Catatan Medik';
            $anakData->kia_ibu_id = $bundaData->id;
            $anakData->save();
            $anakData->init_fundamental_data();

            $anakData = new KiaIdentitasAnak();
            $anakData->nama = 'Anak 2';
            $anakData->anak_ke = 2;
            $anakData->no_akte_kelahiran = '2398383';
            $anakData->nik = '2239838837';
            $anakData->jenis_kelamin = 'L';
            $anakData->gol_darah = 'B';
            $anakData->tempat_lahir = 1101;
            $anakData->tanggal_lahir = '2018-01-01';
            $anakData->no_jkn = 'No JKn';
            $anakData->tanggal_berlaku_jkn = '2020-01-01';
            $anakData->no_kohort = '23973739389';
            $anakData->no_catatan_medik = 'Catatan Medik';
            $anakData->is_janin = false;
            $anakData->kia_ibu_id = $bundaData->id;
            $anakData->save();
            $anakData->init_fundamental_data();

            // get new token
            $params = [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => '5xnEHfLiRiyyRGrez97iEmqzTB4h48IWr5DnHEuO',
                'username' => 'a@a.a',
                'password' => 'password',
                'scope' => '*'
            ];
            $nRequest = Request::create('/oauth/token', 'POST', $params);
            $nRequest->headers->set('Accept', 'application/json');
            $response = app()->handle($nRequest);

            if ($response->getStatusCode() == Constants::RESPONSE_SUCCESS) {
                $loginResponse = json_decode($response->getContent());
                $user = User::where('email', 'a@a.a')->first();
                $user->access_token = $loginResponse->access_token;
                $user->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            echo $e;
            DB::rollBack();
        }
    }
}
