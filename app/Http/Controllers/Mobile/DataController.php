<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Kia;
use App\Models\KiaIdentitasAnak;
use App\Models\KiaIdentitasAyah;
use App\Models\KiaIdentitasIbu;
use App\Models\Kota;
use App\Utils\Constants;
use App\Utils\Traits\Util;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    //
    use Util;

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
