<?php

namespace Tests\Feature;

use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BayikuTest extends TestCase
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
        $this->json('GET', 'api/anaku/overview', [],[
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testCreateReport() {
        $this->json('POST', 'api/anaku/create-monthly-report', [
            'year_id' => 7,
            'date' => '2020-01-01',
            'location' => 'Klinik Damai',
            'age' => 1,
            'bb' => 3,
            'tb' => 30,
            'lingkar_kepala' => 60,
            'imt' => 18,
            'month' => 3,
            'pemeriksa' => 'Dokter Mudah',
            'perkembangan_ans' => [
                [
                    'q_id' => 1,
                    'ans' => 1,
                ],
                [
                    'q_id' => 2,
                    'ans' => 0,
                ],
                [
                    'q_id' => 3,
                    'ans' => 1,
                ],
                [
                    'q_id' => 4,
                    'ans' => 0,
                ],
                [
                    'q_id' => 5,
                    'ans' => 1,
                ],
                [
                    'q_id' => 6,
                    'ans' => 1,
                ],
                [
                    'q_id' => 7,
                    'ans' => 0,
                ],
                [
                    'q_id' => 8,
                    'ans' => 1,
                ],
                [
                    'q_id' => 9,
                    'ans' => 1,
                ],
                [
                    'q_id' => 10,
                    'ans' => 1,
                ],
            ]
        ],[
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetReport() {
        $this->json('POST', 'api/anaku/show-monthly-report', [
            'month' => 1,
            'year_id' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' == 200
        ]);
    }

    public function testGetReportAnalysis() {
        $this->json('POST', 'api/anaku/show-monthly-report-analysis', [
            'month' => 1,
            'year_id' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' == 200
        ]);
    }

    public function testCreateNeonatusKn1() {
        $this->json('POST', 'api/anaku/create-neonatus-kn1', [
            'q_menyusu' => 1,
            'q_tali_pusat' => 1,
            'q_vit_k1' => 1,
            'q_salep' => 1,
            'q_imunisasi_hb' => 1,
            'date' => '2021-02-02',
            'time' => '10:30',
            'no_batch' => '389383',
            'tb' => 38,
            'bb' => 22,
            'lingkar_kepala' => 20,
            'q_skrining_hipotiroid_kongenital' => 1,
            'masalah' => '-',
            'dirujuk_ke' => 'RS Utama',
            'petugas' => 'Petugas',
            'catatan_penting' => '-',
            'monthly_checkup_id' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testCreateNeonatusKn2() {
        $this->json('POST', 'api/anaku/create-neonatus-kn2', [
            'q_menyusu' => 1,
            'q_tali_pusat' => 1,
            'q_vit_k1' => 1,
            'q_identifikasi_kuning' => 1,
            'q_imunisasi_hb' => 1,
            'date' => '2021-02-02',
            'time' => '10:30',
            'no_batch' => '389383',
            'tb' => 38,
            'bb' => 22,
            'lingkar_kepala' => 20,
            'q_skrining_hipotiroid_kongenital' => 1,
            'masalah' => '-',
            'dirujuk_ke' => 'RS Utama',
            'petugas' => 'Petugas',
            'catatan_penting' => '-',
            'monthly_checkup_id' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' == 200
        ]);
    }

    public function testCreateNeonatusKn3() {
        $this->json('POST', 'api/anaku/create-neonatus-kn3', [
            'q_menyusu' => 1,
            'q_tali_pusat' => 1,
            'q_vit_k1' => 1,
            'q_salep' => 1,
            'q_imunisasi_hb' => 1,
            'q_kuning1' => 0,
            'q_kuning2' => 0,
            'q_kuning3' => 0,
            'q_kuning4' => 0,
            'q_kuning5' => 0,
            'masalah' => '-',
            'dirujuk_ke' => 'RS Utama',
            'petugas' => 'Petugas',
            'catatan_penting' => '-',
            'monthly_checkup_id' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' == 200
        ]);
    }

    public function testCreateNeonatus6Hours() {
        $this->json('POST', 'api/anaku/create-neonatus-6-hours', [
            'bb' => 10,
            'tb' => 45,
            'lingkar_kepala' => 30,
            'q_imd' => 1,
            'q_vit_k1' => 1,
            'q_salep' => 1,
            'q_imunisasi_hb' => 1,
            'date' => '2021-01-01',
            'time' => '10:00',
            'no_batch' => '398383',
            'dirujuk_ke' => 'RS Utama',
            'petugas' => 'Petugas',
            'catatan_penting' => '-',
            'monthly_checkup_id' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' == 200
        ]);
    }

    public function testGetPerkembanganQuestionaire() {
        $this->json('GET', 'api/anaku/perkembangan-questionnaire/3', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' == 200
        ]);
    }

    public function testGetImmunization() {
        $this->json('get', 'api/anaku/immunization/1', [],
            ['Authorization' => 'Bearer ' . Constants::getAccessToken()])->assertOk();
    }

    public function testCreateImmunization() {
        $this->json('POST', 'api/anaku/immunization',[
            'id' => 19,
            'date' => '2020-02-02',
            'location' => 'ITS',
            'pic' => 'Dr. P',
            'no_batch' => '298398389'
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertSee([
            'code' => 200
        ]);
    }

    public function testGetKmsGraph() {
        $this->json('GET', 'api/anaku/graph/kms/2', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetBbUsiaGraph() {
        $this->json('GET', 'api/anaku/graph/bb-usia/2', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetPbUsiaGraph() {
        $this->json('GET', 'api/anaku/graph/pb-usia/2', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetBbPbGraph() {
        $this->json('GET', 'api/anaku/graph/bb-pb/2', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetLingkarKepalaGraph() {
        $this->json('GET', 'api/anaku/graph/lingkar-kepala/2', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetImtGraph() {
        $this->json('GET', 'api/anaku/graph/imt/2', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetPerkembanganGraph() {
        $this->json('GET', 'api/anaku/graph/perkembangan/2', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }
}
