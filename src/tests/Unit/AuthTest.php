<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\JWTService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_user_id_by_username()
    {
        $user = User::factory()->create(['name'=> 'test']);
        $repo = new UserRepository();
        $userId = $repo->getUserIdByUsername('test');
        $this->assertEquals($user->id, $userId);
    }

    public function test_register_user_by_params() {
        $repo = new UserRepository();
        $jwtToken = $repo->registerUserByParams('test','password');
        $user = User::where('name', 'test')->first();
        $this->assertTrue(JWTService::isTokenValid($jwtToken, $user->jwt_secret));
    }

    public function test_authenticate_user_by_params() {
        $user = User::factory()->create(['name'=> 'test']);
        $repo = new UserRepository();
        $jwtToken = $repo->authenticateUserByParams('test','password');
        $this->assertTrue(JWTService::isTokenValid($jwtToken, $user->jwt_secret));
        $wrongPasswordResponse = $repo->authenticateUserByParams('test','wrongpassword');
        $this->assertNull($wrongPasswordResponse);
    }
}
