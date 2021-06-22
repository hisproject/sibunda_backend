<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\KiaIdentitasAnak;
use App\Models\KiaIdentitasAyah;
use App\Models\KiaIdentitasIbu;
use App\Models\User;
use App\Utils\Constants;
use App\Utils\Traits\Util;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataController extends Controller
{
    //
    use Util;

    public function createBundaUser(Request $request) {
        // validasi
        $userValidation = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);
        $userValidation['user_group_id'] = Constants::USER_GROUP_BUNDA;
        // validasi data ibu
        $bundaValidation = $request->validate([
            'bunda_nama' => 'required',
            'bunda_nik' => 'required',
            'bunda_pembiayaan' => 'string',
            'bunda_no_jkn' => 'string',
            'bunda_faskes_tk1' => 'string',
            'bunda_faskes_rujukan' => 'string',
            'bunda_gol_darah' => 'string|max:2',
            'bunda_tempat_lahir' => 'integer',
            'bunda_tanggal_lahir' => 'date',
            'bunda_pendidikan' => 'string',
            'bunda_pekerjaan' => 'string',
            'bunda_alamat_rumah' => 'string',
            'bunda_telp' => 'string',
            'bunda_puskesmas_domisili' => 'string',
            'bunda_nomor_register_kohort_ibu' => 'string'
        ]);
        // validaasi data ayah
        $ayahValidation = $request->validate([
            'ayah_nama' => 'required',
            'ayah_nik' => 'required',
            'ayah_pembiayaan' => 'string',
            'ayah_no_jkn' => 'string',
            'ayah_faskes_tk1' => 'string',
            'ayah_faskes_rujukan' => 'string',
            'ayah_gol_darah' => 'string|max:2',
            'ayah_tempat_lahir' => 'integer',
            'ayah_tanggal_lahir' => 'date',
            'ayah_pendidikan' => 'string',
            'ayah_pekerjaan' => 'string',
            'ayah_alamat_rumah' => 'string',
            'ayah_telp' => 'string',
        ]);
        // validasi data anak
        $request->validate([
            'anak' => 'array|required',
            'anak.*.nama' => 'string',
            'anak.*.anak_ke' => 'integer',
            'anak.*.no_akte_kelahiran' => 'string',
            'anak.*.nik' => 'string',
            'anak.*.gol_darah' => 'string|max:2',
            'anak.*.tempat_lahir' => 'integer|required',
            'anak.*.tanggal_lahir' => 'date',
            'anak.*.no_jkn' => 'string',
            'anak.*.tanggal_berlaku_jkn' => 'date',
            'anak.*.no_kohort' => 'string',
            'anak.*.no_catatan_medik' => 'string'
        ]);

        DB::beginTransaction();
        try {
            $userValidation['password'] = Hash::make($userValidation['password']);
            $user = User::create($userValidation);
            $bundaValidation['user_id'] = $user->id;
            $bundaData = KiaIdentitasIbu::create([
                'nama' => $request->bunda_nama,
                'nik' => $request->bunda_nik,
                'pembiayaan' => $request->bunda_pembiayaan,
                'no_jkn' => $request->bunda_no_jkn,
                'faskes_tk1' => $request->bunda_faskes_tk1,
                'faskes_rujukan' => $request->bunda_faskes_rujukan,
                'gol_darah' => $request->bunda_gol_darah,
                'tempat_lahir' => $request->bunda_tempat_lahir,
                'tanggal_lahir' => $request->bunda_tanggal_lahir,
                'pendidikan' => $request->bunda_pendidikan,
                'pekerjaan' => $request->bunda_pekerjaan,
                'alamat_rumah' => $request->bunda_alamat_rumah,
                'telp' => $request->bunda_telp,
                'puskesmas_domisili' => $request->bunda_puskesmas_domisili,
                'nomor_register_kohort_ibu' => $request->bunda_nomor_register_kohort_ibu,
                'user_id' => $user->id
            ]);
            $bundaData->init_fundamental_data();
            $ayahValidation['kia_ibu_id'] = $bundaData->id;
            KiaIdentitasAyah::create([
                'nama' => $request->ayah_nama,
                'nik' => $request->ayah_nik,
                'pembiayaan' => $request->ayah_pembiayaan,
                'no_jkn' => $request->ayah_no_jkn,
                'faskes_tk1' => $request->ayah_faskes_tk1,
                'faskes_rujukan' => $request->ayah_faskes_rujukan,
                'gol_darah' => $request->ayah_gol_darah,
                'tempat_lahir' => $request->ayah_tempat_lahir,
                'tanggal_lahir' => $request->ayah_tanggal_lahir,
                'pendidikan' => $request->ayah_pendidikan,
                'pekerjaan' => $request->ayah_pekerjaan,
                'alamat_rumah' => $request->ayah_alamat_rumah,
                'telp' => $request->ayah_telp,
                'kia_ibu_id' => $bundaData->id
            ]);
            foreach($request->anak as $anak) {
                $anakData = new KiaIdentitasAnak();
                $anakData->nama = $this->nullableVal($anak['nama']);
                $anakData->anak_ke = $this->nullableVal($anak['anak_ke']);
                $anakData->no_akte_kelahiran = $this->nullableVal($anak['no_akte_kelahiran']);
                $anakData->nik = $this->nullableVal($anak['nik']);
                $anakData->gol_darah = $this->nullableVal($anak['gol_darah']);
                $anakData->tempat_lahir = $this->nullableVal($anak['tempat_lahir']);
                $anakData->tanggal_lahir = $this->nullableVal($anak['tanggal_lahir']);
                $anakData->no_jkn = $this->nullableVal($anak['no_jkn']);
                $anakData->tanggal_berlaku_jkn = $this->nullableVal($anak['tanggal_berlaku_jkn']);
                $anakData->no_kohort = $this->nullableVal($anak['no_kohort']);
                $anakData->no_catatan_medik = $this->nullableVal($anak['no_catatan_medik']);
                $anakData->kia_ibu_id = $bundaData->id;
                $anakData->save();
                $anakData->init_fundamental_data();
            }
            DB::commit();
            return Constants::successResponseWithNewValue('user', $user);
        } catch (Exception $e) {
            DB::rollBack();
            return Constants::errorResponse();
        }
    }


    public function createDataIbu(Request $request) {
        $validation = $this->validate($request, [
            'nama' => 'required',
            'nik' => 'required',
            'pembiayaan' => 'string',
            'no_jkn' => 'string',
            'faskes_tk1' => 'string',
            'faskes_rujukan' => 'string',
            'gol_darah' => 'string|max:2',
            'tempat_lahir' => 'integer',
            'tanggal_lahir' => 'date',
            'pendidikan' => 'string',
            'pekerjaan' => 'string',
            'alamat_rumah' => 'string',
            'telp' => 'string',
            'puskesmas_domisili' => 'string',
            'nomor_register_kohort_ibu' => 'string',
        ]);

        DB::beginTransaction();
        try {
            $kia = Kia::where('user_id', Auth::id())->first();

            if(empty($kia->kia_ibu_id)) {
                $kiaIdentitasIbuId = KiaIdentitasIbu::create($validation)->id;
                $kia->kia_ibu_id = $kiaIdentitasIbuId;
                $kia->save();
            }

            DB::commit();
            return Constants::successResponse('Identitas Ibu Created');
        } catch (Exception $e) {
            DB::rollBack();
            return Constants::errorResponse();
        }
    }

    public function updateDataIbu(Request $request, $dataIbuId) {
        $validation = $this->validate($request, [
            'nama' => 'string',
            'nik' => 'string',
            'pembiayaan' => 'string',
            'no_jkn' => 'string',
            'faskes_tk1' => 'string',
            'faskes_rujukan' => 'string',
            'gol_darah' => 'string|max:2',
            'tempat_lahir' => 'integer',
            'tanggal_lahir' => 'date',
            'pendidikan' => 'string',
            'pekerjaan' => 'string',
            'alamat_rumah' => 'string',
            'telp' => 'string',
            'puskesmas_domisili' => 'string',
            'nomor_register_kohort_ibu' => 'string',
        ]);

        $kiaIdentitasIbu = KiaIdentitasIbu::find($dataIbuId);
        $kiaIdentitasIbu->nama = $this->filterNullVal($kiaIdentitasIbu->nama, $request->nama);
        $kiaIdentitasIbu->nik = $this->filterNullVal($kiaIdentitasIbu->nik, $request->nik);
        $kiaIdentitasIbu->pembiayaan = $this->filterNullVal($kiaIdentitasIbu->pembiayaan, $request->pembiayaan);
        $kiaIdentitasIbu->no_jkn = $this->filterNullVal($kiaIdentitasIbu->no_jkn, $request->no_jkn);
        $kiaIdentitasIbu->faskes_tk1 = $this->filterNullVal($kiaIdentitasIbu->faskes_tk1, $request->faskes_tk1);
        $kiaIdentitasIbu->faskes_rujukan = $this->filterNullVal($kiaIdentitasIbu->faskes_rujukan, $request->faskes_rujukan);
        $kiaIdentitasIbu->gol_darah = $this->filterNullVal($kiaIdentitasIbu->gol_darah, $request->gol_darah);
        $kiaIdentitasIbu->tempat_lahir = $this->filterNullVal($kiaIdentitasIbu->tempat_lahir, $request->tempat_lahir);
        $kiaIdentitasIbu->tanggal_lahir = $this->filterNullVal($kiaIdentitasIbu->tanggal_lahir, $request->tanggal_lahir);
        $kiaIdentitasIbu->pendidikan = $this->filterNullVal($kiaIdentitasIbu->pendidikan, $request->pendidikan);
        $kiaIdentitasIbu->pekerjaan = $this->filterNullVal($kiaIdentitasIbu->pekerjaan, $request->pekerjaan);
        $kiaIdentitasIbu->alamat_rumah = $this->filterNullVal($kiaIdentitasIbu->alamat_rumah, $request->alamat_rumah);
        $kiaIdentitasIbu->telp = $this->filterNullVal($kiaIdentitasIbu->telp, $request->telp);
        $kiaIdentitasIbu->puskesmas_domisili = $this->filterNullVal($kiaIdentitasIbu->puskesmas_domisili, $request->puskesmas_domisili);
        $kiaIdentitasIbu->nomor_register_kohort_ibu = $this->filterNullVal($kiaIdentitasIbu->nomor_register_kohort_ibu, $request->nomor_register_kohort_ibu);
        $kiaIdentitasIbu->save();

        return Constants::successResponse('Identitas Ibu Updated');
    }

    public function createDataAyah(Request $request) {
        $validation = $this->validate($request, [
            'nama' => 'required',
            'nik' => 'required',
            'pembiayaan' => 'string',
            'no_jkn' => 'string',
            'faskes_tk1' => 'string',
            'faskes_rujukan' => 'string',
            'gol_darah' => 'string|max:2',
            'tempat_lahir' => 'integer',
            'tanggal_lahir' => 'date',
            'pendidikan' => 'string',
            'pekerjaan' => 'string',
            'alamat_rumah' => 'string',
            'telp' => 'string',
        ]);

        DB::beginTransaction();
        try {
            $kia = Kia::where('user_id', Auth::id())->first();

            if (empty($kia->kia_ayah_id)) {
                $kiaIdentitasAyahId = KiaIdentitasAyah::create($validation)->id;
                $kia->kia_ayah_id = $kiaIdentitasAyahId;
                $kia->save();
            }

            DB::commit();
            return Constants::successResponse('Identitas Ayah Created');
        } catch (Exception $e) {
            DB::rollBack();
            return Constants::errorResponse();
        }
    }

    public function updateDataAyah(Request $request, $dataAyahId) {
        $validation = $this->validate($request, [
            'nama' => 'string',
            'nik' => 'string',
            'pembiayaan' => 'string',
            'no_jkn' => 'string',
            'faskes_tk1' => 'string',
            'faskes_rujukan' => 'string',
            'gol_darah' => 'string|max:2',
            'tempat_lahir' => 'integer',
            'tanggal_lahir' => 'date',
            'pendidikan' => 'string',
            'pekerjaan' => 'string',
            'alamat_rumah' => 'string',
            'telp' => 'string',
            'puskesmas_domisili' => 'string',
            'nomor_register_kohort_ibu' => 'string',
        ]);

        $kiaIdentitasAyah = KiaIdentitasAyah::find($dataAyahId);
        $kiaIdentitasAyah->nama = $this->filterNullVal($kiaIdentitasAyah->nama, $request->nama);
        $kiaIdentitasAyah->nik = $this->filterNullVal($kiaIdentitasAyah->nik, $request->nik);
        $kiaIdentitasAyah->pembiayaan = $this->filterNullVal($kiaIdentitasAyah->pembiayaan, $request->pembiayaan);
        $kiaIdentitasAyah->no_jkn = $this->filterNullVal($kiaIdentitasAyah->no_jkn, $request->no_jkn);
        $kiaIdentitasAyah->faskes_tk1 = $this->filterNullVal($kiaIdentitasAyah->faskes_tk1, $request->faskes_tk1);
        $kiaIdentitasAyah->faskes_rujukan = $this->filterNullVal($kiaIdentitasAyah->faskes_rujukan, $request->faskes_rujukan);
        $kiaIdentitasAyah->gol_darah = $this->filterNullVal($kiaIdentitasAyah->gol_darah, $request->gol_darah);
        $kiaIdentitasAyah->tempat_lahir = $this->filterNullVal($kiaIdentitasAyah->tempat_lahir, $request->tempat_lahir);
        $kiaIdentitasAyah->tanggal_lahir = $this->filterNullVal($kiaIdentitasAyah->tanggal_lahir, $request->tanggal_lahir);
        $kiaIdentitasAyah->pendidikan = $this->filterNullVal($kiaIdentitasAyah->pendidikan, $request->pendidikan);
        $kiaIdentitasAyah->pekerjaan = $this->filterNullVal($kiaIdentitasAyah->pekerjaan, $request->pekerjaan);
        $kiaIdentitasAyah->alamat_rumah = $this->filterNullVal($kiaIdentitasAyah->alamat_rumah, $request->alamat_rumah);
        $kiaIdentitasAyah->telp = $this->filterNullVal($kiaIdentitasAyah->telp, $request->telp);
        $kiaIdentitasAyah->save();

        return Constants::successResponse('Identitas Ayah Updated');
    }

    public function createDataAnak(Request $request) {
        $validation = $this->validate($request, [
            'nama' => 'required',
            'anak_ke' => 'integer|required',
            'no_akte_kelahiran' => 'string',
            'nik' => 'string',
            'gol_darah' => 'string|max:2',
            'tempat_lahir' => 'integer',
            'tanggal_lahir' => 'date',
            'no_jkn' => 'string',
            'tanggal_berlaku_jkn' => 'date',
            'no_kohort' => 'string',
            'no_catatan_medik' => 'string'
        ]);

        DB::beginTransaction();
        try {
            $kia = Kia::where('user_id', Auth::id())->first();

            if(empty($kia->kia_anak_id)) {
                $kiaIdentitasAnakId = KiaIdentitasAnak::create($validation)->id;
                $kia->kia_anak_id = $kiaIdentitasAnakId;
                $kia->save();
            }
            DB::commit();
            return Constants::successResponse('Identitas Anak Created');
        } catch(Exception $e) {
            DB::rollback();
            return Constants::errorResponse();
        }
    }

    public function updateDataAnak(Request $request, $dataAnakId) {
        $validation = $this->validate($request, [
            'nama' => 'required',
            'anak_ke' => 'integer|required',
            'no_akte_kelahiran' => 'string',
            'nik' => 'string',
            'gol_darah' => 'string|max:2',
            'tempat_lahir' => 'integer',
            'tanggal_lahir' => 'date',
            'no_jkn' => 'string',
            'tanggal_berlaku_jkn' => 'date',
            'no_kohort' => 'string',
            'no_catatan_medik' => 'string'
        ]);

        $kiaIdentitasAnak = KiaIdentitasAnak::find($dataAnakId);
        $kiaIdentitasAnak->nama = $this->filterNullVal($kiaIdentitasAnak->nama, $request->nama);
        $kiaIdentitasAnak->anak_ke = $this->filterNullVal($kiaIdentitasAnak->anak_ke, $request->anak_ke);
        $kiaIdentitasAnak->no_akte_kelahiran = $this->filterNullVal($kiaIdentitasAnak->no_akte_kelahiran, $request->no_akte_kelahiran);
        $kiaIdentitasAnak->nik = $this->filterNullVal($kiaIdentitasAnak->nik, $request->nik);
        $kiaIdentitasAnak->gol_darah = $this->filterNullVal($kiaIdentitasAnak->gol_darah, $request->gol_darah);
        $kiaIdentitasAnak->tempat_lahir = $this->filterNullVal($kiaIdentitasAnak->tempat_lahir, $request->tempat_lahir);
        $kiaIdentitasAnak->tanggal_lahir = $this->filterNullVal($kiaIdentitasAnak->tanggal_lahir, $request->tanggal_lahir);
        $kiaIdentitasAnak->no_jkn = $this->filterNullVal($kiaIdentitasAnak->no_jkn, $request->no_jkn);
        $kiaIdentitasAnak->tanggal_berlaku_jkn = $this->filterNullVal($kiaIdentitasAnak->tanggal_berlaku_jkn, $request->tanggal_berlaku_jkn);
        $kiaIdentitasAnak->no_kohort = $this->filterNullVal($kiaIdentitasAnak->no_kohort, $request->no_kohort);
        $kiaIdentitasAnak->no_catatan_medik = $this->filterNullVal($kiaIdentitasAnak->no_catatan_medik, $request->no_catatan_medik);
        $kiaIdentitasAnak->save();

        return Constants::successResponse('Identitas Anak Updated');
    }

    // master data
    public function getKota() {
        $data = DB::select('select id, trim(nama) as nama from kota order by id');

        return $data;
    }
}