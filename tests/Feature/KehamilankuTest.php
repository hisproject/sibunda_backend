<?php

namespace Tests\Feature;

use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KehamilankuTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testOverview() {
        $this->json('GET', 'api/kehamilanku/overview', [],[
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testCreateReport() {
        $this->json('POST', 'api/kehamilanku/create-weekly-report', [
          'week' => 8,
          'tanggal_periksa' => '2020-01-01',
          'tempat_periksa' => 'Klinik Damai',
          'nama_pemeriksa' => 'Dr. Damai',
            'keluhan_bunda' => 'Keluhan',
            'jenis_kelamin' => 'L',
            'tanggal_periksa_kembali' => '2020-01-07',
            'hpl' => '2020-01-28',
            'bb' => 1,
            'kenaikan_bb' => 0.5,
            'tb' => 10,
            'tfu' => 10,
            'djj' => 10,
            'sistolik' => 100,
            'diastolik' => 90,
            'map' => 45,
            'gerakan_bayi' => 10,
            'resep_obat' => 'Resep Obat',
            'alergi_obat' => '-',
            'riwayat_penyakit' => '-',
            'catatan_khusus' => '-',
            'trisemester_id' => 1
        ],[
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetReport() {
        $this->json('POST', 'api/kehamilanku/show-weekly-report', [
            'trisemester_id' => 1,
            'week' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertSee([
            'code' == 200
        ]);
    }

    public function testGetReportAnalysis() {
        $this->json('POST', 'api/kehamilanku/show-weekly-report-analysis',[
            'weekly_trisemester_checkup_id' =>1
        ], [
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetImmunization() {
        $this->json('get', 'api/kehamilanku/immunization', [],
        ['Authorization' => 'Bearer ' . Constants::getDummyAccessToken()])->assertOk();
    }

    public function testCreateImmunization() {
        $this->json('POST', 'api/kehamilanku/immunization',[
            'immunization_id' => 1,
            'date' => '2020-02-02',
            'location' => 'ITS',
            'pic' => 'Dr. P'
        ], [
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetTfuGraph() {
        $this->json('GET', 'api/kehamilanku/graph/tfu/1', [],
        ['Authorization' => 'Bearer ' . Constants::getDummyAccessToken()])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetDjjGraph() {
        $this->json('GET', 'api/kehamilanku/graph/djj/1', [],
            ['Authorization' => 'Bearer ' . Constants::getDummyAccessToken()])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetMapGraph() {
        $this->json('GET', 'api/kehamilanku/graph/map/1', [],
            ['Authorization' => 'Bearer ' . Constants::getDummyAccessToken()])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetMomWeightGraph() {
        $this->json('GET', 'api/kehamilanku/graph/mom-weight/1', [],
            ['Authorization' => 'Bearer ' . Constants::getDummyAccessToken()])->assertSee([
            'code' => 200
        ]);
    }
}
