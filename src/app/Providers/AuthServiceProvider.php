<?php

namespace App\Providers;

use App\Models\User;
use App\Services\JWTService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('token', function (Request $request) {
            $token = $request->bearerToken();
            $userId = JWTService::getClaimFromToken($token, 'id');
            $isTokenTypeRefresh = JWTService::getClaimFromToken($token, 'type') === 'refresh';
            if (!$userId || $isTokenTypeRefresh) {
                return null;
            }
            $user = User::find($userId);
            if ($user) {
                return JWTService::isTokenValid($token, $user->jwt_secret) ? $user : null;
            }
            return null;
        });
    }
}
