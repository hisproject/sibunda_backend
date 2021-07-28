<?php

namespace Tests\Feature;

use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DataTest extends TestCase
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

    public function testBio() {
        $this->json('GET', 'api/data/bio', [],[
            'Authorization' => 'Bearer ' . Constants::getAccessToken()
        ])->assertOk();
    }
}
