<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    /**
     * Login user and return tokens
     *
     * @param array $credentials
     * @return array{access_token: string, refresh_token: string, expires_in: int, token_type: string}
     * @throws AuthenticationException
     */
    public function login(array $credentials): array
    {
        $accessTokenTTL = config('jwt.ttl.access_token', 60);
        $refreshTokenTTL = config('jwt.ttl.refresh_token', 21600);

        // Attempt to authenticate and get access token
        $accessToken = auth('api')->setTTL($accessTokenTTL)->attempt($credentials);

        if (!$accessToken) {
            throw new AuthenticationException('Invalid email or password');
        }

        $user = auth('api')->user();

        if (!$user) {
            throw new AuthenticationException('Unable to retrieve user after authentication');
        }

        // Generate refresh token
        $refreshToken = auth('api')->setTTL($refreshTokenTTL)->fromUser($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => $accessTokenTTL * 60, // Convert to seconds
            'token_type' => config('jwt.token_type', 'bearer'),
        ];
    }

    /**
     * Refresh access token
     *
     * @return array{access_token: string, expires_in: int, token_type: string}
     * @throws AuthenticationException
     */
    public function refresh(): array
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                throw new AuthenticationException('User not authenticated');
            }

            $accessTokenTTL = config('jwt.ttl.access_token', 60);
            $newAccessToken = auth('api')->setTTL($accessTokenTTL)->fromUser($user);

            return [
                'access_token' => $newAccessToken,
                'expires_in' => $accessTokenTTL * 60, // Convert to seconds
                'token_type' => config('jwt.token_type', 'bearer'),
            ];
        } catch (JWTException $e) {
            throw new AuthenticationException('Unable to refresh token: ' . $e->getMessage());
        }
    }

    /**
     * Logout user
     *
     * @return bool
     * @throws AuthenticationException
     */
    public function logout(): bool
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                throw new AuthenticationException('No token found');
            }

            JWTAuth::invalidate($token);
            return true;
        } catch (JWTException $e) {
            throw new AuthenticationException('Unable to logout: ' . $e->getMessage());
        }
    }

    /**
     * Get authenticated user
     *
     * @return User|null
     * @throws AuthenticationException
     */
    public function me(): ?User
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                throw new AuthenticationException('User not authenticated');
            }

            return $user;
        } catch (JWTException $e) {
            throw new AuthenticationException('Unable to retrieve user: ' . $e->getMessage());
        }
    }
}
