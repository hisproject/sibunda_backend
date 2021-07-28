<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testLogin() {
        $this->json('POST', 'api/auth/login', [
            'client_id' => 2,
            'client_secret' => '5xnEHfLiRiyyRGrez97iEmqzTB4h48IWr5DnHEuO',
            'email' => 'a@a.a',
            'password' => 'password',
            'password_confirmation' => 'password',
            'fcm_token' => 'it should be an fcm token',
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testRegister() {
        $this->json('POST', 'api/auth/register', [
            'name' => 'Bunda',
            'email' => 'bunda2@a.a',
            'password' => 'password',
            'password_confirmation' => 'password',
            'bunda_nama' => 'Bunda',
            'bunda_nik' => '35783838383',
            'bunda_pembiayaan' => 'Asuransi',
            'bunda_no_jkn' => 'jkn-283838',
            'bunda_faskes_tk1' => 'Klinik Damai',
            'bunda_faskes_rujukan' => 'RS Utama',
            'bunda_gol_darah' => 'O',
            'bunda_tempat_lahir' => 3578,
            'bunda_tanggal_lahir' => '1970-01-01',
            'bunda_pendidikan' => 'S1 Ekonomi',
            'bunda_pekerjaan' => 'Konsultan Ekonomi',
            'bunda_alamat_rumah' => 'Surabaya',
            'bunda_telp' => '08230000000',
            'bunda_puskesmas_domisili' => 'Puskesmas Damai',
            'bunda_nomor_register_kohort_ibu' => '0938983983',
            'ayah_nama' => 'Ayah',
            'ayah_nik' => '357838383821',
            'ayah_pembiayaan' => 'Asuransi',
            'ayah_no_jkn' => 'jkn-23838',
            'ayah_faskes_tk1' => 'Klinik Damai',
            'ayah_faskes_rujukan' => 'RS Utama',
            'ayah_gol_darah' => 'AB',
            'ayah_tempat_lahir' => 3578,
            'ayah_tanggal_lahir' => '1969-01-01',
            'ayah_pendidikan' => 'S1 Teknik Informatika',
            'ayah_pekerjaan' => 'CTO',
            'ayah_alamat_rumah' => 'Surabaya',
            'anak' => [
                [
                    'nama' => 'Donald',
                    'anak_ke' => '1',
                    'no_akte_kelahiran' => 'Donald',
                    'nik' => '357835838',
                    'gol_darah' => 'AB',
                    'tempat_lahir' => 3578,
                    'tanggal_lahir' => '2019-01-01',
                    'no_jkn' => 'jkn-298383',
                    'tanggal_berlaku_jkn' => '2020-01-01',
                    'no_kohort' => '32983983',
                    'no_catatan_medik' => '3983983983',
                    'jenis_kelamin' => 'L',
                ]
            ],
            'janin_hpl' => '2021-01-01'
        ])->assertSee([
            'code' => 200
        ]);
    }
}
