<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SendCoinTest extends TestCase
{
    use DatabaseTransactions;

    public function test_send_coin_after_registration()
    {
        User::factory()->create(['name' => 'receiver']);
        $response = $this->post(
            '/api/sendCoin',
            ['toUser' => 'receiver', 'amount' => 600],
            ['Authorization' => 'Wrongtoken', 'Accept' => 'application/json']
        );
        $response->assertStatus(401);
        $response = $this->post('/api/auth', ['password' => 'password', 'username' => 'test']);
        $jwtToken = json_decode($response->content(), true)['token'];
        $response = $this->post(
            '/api/sendCoin',
            ['toUser' => 'wrong', 'amount' => 600],
            ['Authorization' => 'Bearer ' . $jwtToken, 'Accept' => 'application/json']
        );
        $response->assertStatus(400);
        $response = $this->post(
            '/api/sendCoin',
            ['toUser' => 'receiver', 'amount' => 600],
            ['Authorization' => 'Bearer ' . $jwtToken, 'Accept' => 'application/json']
        );
        $response->assertStatus(200);
        $response = $this->post(
            '/api/sendCoin',
            ['toUser' => 'receiver', 'amount' => 600],
            ['Authorization' => 'Bearer ' . $jwtToken, 'Accept' => 'application/json']
        );
        $response->assertStatus(400);
    }
}
