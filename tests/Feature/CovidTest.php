<?php

namespace Tests\Feature;

use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CovidTest extends TestCase
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

    public function testGetCovidFormHistory() {
        $this->json('GET', 'api/covid/checkup', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetCovidFormQuestionnaire() {
        $this->json('GET', 'api/covid/questionnaire', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testGetAllAnak() {
        $this->json('GET', 'api/covid/anak', [], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }

    public function testCreateCovidForm() {
        $this->json('POST', 'api/covid/checkup', [
            'is_ibu' => false,
            'date' => '2021-01-01',
            'answers' => [
                ['q_id' => 1, 'ans' => 0],
                ['q_id' => 2, 'ans' => 1],
                ['q_id' => 3, 'ans' => 0],
                ['q_id' => 4, 'ans' => 1],
                ['q_id' => 5, 'ans' => 1],
                ['q_id' => 6, 'ans' => 1],
                ['q_id' => 7, 'ans' => 0],
            ],
            'anak_id' => 1
        ], [
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }
}
