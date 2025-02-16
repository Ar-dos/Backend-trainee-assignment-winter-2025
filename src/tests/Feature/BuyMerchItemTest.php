<?php

namespace Tests\Feature;

use App\Models\Merch;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuyMerchItemTest extends TestCase
{

    use DatabaseTransactions;

    public function test_buy_merch_after_registration()
    {
        Merch::factory()->create(['name' => 'merch', 'price' => 1000]);
        $response = $this->get('/api/buy/merch', ['Authorization' => 'Wrongtoken', 'Accept' => 'application/json']);
        $response->assertStatus(401);
        $response = $this->post('/api/auth', ['password' => 'password', 'username' => 'test']);
        $jwtToken = json_decode($response->content(), true)['token'];
        $response = $this->get('/api/buy/wrong', ['Authorization' => 'Bearer ' . $jwtToken]);
        $response->assertStatus(400);
        $response = $this->get('/api/buy/merch', ['Authorization' => 'Bearer ' . $jwtToken]);
        $response->assertStatus(200);
    }
}
