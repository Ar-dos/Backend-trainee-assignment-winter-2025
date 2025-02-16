<?php

return [
    /*
     * JWT secret
     */
    'secret' => env('JWT_SECRET', 'secret'),
    /*
     * TTL of refresh token. Default is 30 days (43200 mins)
     */
    'refreshTTL' => env('JWT_REFRESH_TTL', 43200),
    /*
     * TTL of access token. Default is 90 min
     */
    'accessTTL' => env('JWT_ACCESS_TTL', 90),
    /*
     * If param equals true user secret on logout will be change
     * so all issued token (both refresh and access) will become invalid.
     */
    'changeUserSecretOnLogout' => env('CHANGE_USER_SECRET_ON_LOGOUT', false),
];
