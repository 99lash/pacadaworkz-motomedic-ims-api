<?php

namespace App\Services;

use App\Models\RefreshToken;
use Illuminate\Support\Str;

class RefreshTokenService
{
    public static function create($userId, $expiresAt = null) {
        return RefreshToken::create([
            'user_id' => $userId,
            'token' => Str::random(64),
            'expires_at' => $expiresAt,
        ]);
    }

    public static function findByToken($token) {
        return RefreshToken::where('token', $token)->first();
    }

    public static function revoke($token) {
        $refreshToken = self::findByToken($token);
        if ($refreshToken) {
            $refreshToken->revoked = true;
            $refreshToken->save();
        }
        return $refreshToken;
    }
}
