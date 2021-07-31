<?php

namespace Tests\Feature;

use App\Utils\Constants;
use env;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    public function testGetNotification() {
        $this->json('GET', 'api/dashboard/notifications', [],[
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertOk();
    }

    public function testGetMessage() {
        $this->json('GET', 'api/dashboard/messages', [],[
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertOk();
    }

    public function testGetHomeData() {
        $this->json('GET', 'api/dashboard/index', [],[
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertOk();
    }

    public function testGetTipsInfoCarousel() {
        $this->json('GET', 'api/dashboard/tips-dan-info/carousel', [],[
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertOk();
    }

    public function testGetTipsInfoLatest() {
        $this->json('GET', 'api/dashboard/tips-dan-info/latest', [],[
            'Authorization' => 'Bearer ' . Constants::getDummyAccessToken()
        ])->assertOk();
    }
}
