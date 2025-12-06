<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    // Login user and return token
    public function login(array $credentials)
    {
        // access token 60 mins
        $accessToken = auth('api')->setTTL(60)->attempt($credentials);

        if (!$accessToken)
            return false;

        $user = auth('api')->user();
        //refresh token 15 days
        $refreshToken = auth('api')->setTTL(21600)->fromUser($user);
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }


    public function refresh()
    {
        $user = auth('api')->user();

        $new_access_token = auth('api')->setTTL(60)->fromUser($user);

        return [
            'new_access_token' => $new_access_token,
            'token_type'   => 'bearer',
            'expires_in'   => 60 * 60

        ];
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return true;
    }

    // Get authenticated user
    public function me()
    {
        return JWTAuth::user();
    }
}
