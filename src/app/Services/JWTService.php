<?php

namespace App\Services;

use Firebase\JWT\{JWT, Key};

class JWTService
{
    public static function getClaimFromToken($token, $claimName)
    {
        if (!$token && !str_contains($token, '.')) {
            return null;
        }
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
        return array_key_exists($claimName, $payload) ? $payload[$claimName] : null;
    }

    public static function isTokenValid($token, $userJWTSecret)
    {
        try {
            $key = new Key(config('jwt.secret') . $userJWTSecret, 'HS256');
            $decodedPayload = JWT::decode($token, $key);
            return true;
        } catch (\Exception $e) {
            logger($e);
            return false;
        }
    }

//    public static function getRefreshToken($userId, $userJWTSecret)
//    {
//        $payload = array_merge(
//            self::getRegisteredClaims(config('jwt.refreshTTL')),
//            [
//                'id' => $userId,
//                'type' => 'refresh',
//            ]
//        );
//        $key = config('jwt.secret') . $userJWTSecret;
//        $algo = 'HS256';
//        return JWT::encode($payload, $key, $algo);
//    }

    public static function getAccessToken($userId, $userJWTSecret)
    {
        $payload = array_merge(
            self::getRegisteredClaims(config('jwt.accessTTL')),
            ['id' => $userId]
        );
        $key = config('jwt.secret') . $userJWTSecret;
        $algo = 'HS256';
        return JWT::encode($payload, $key, $algo);
    }

    private static function getRegisteredClaims($ttlInMinutes)
    {
        return [
            'iss' => config('app.name'),
            'iat' => now()->getTimestamp(),
            'exp' => now()->addMinutes($ttlInMinutes)->getTimestamp(),
            'jti' => \Illuminate\Support\Str::random(),
        ];
    }
}
