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
        if (!$token = JWTAuth::attempt($credentials)) {
            return false;
        }

        return $token;
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


    public function register(array $data)
    {
        $user = User::create([
            'role_id' =>$data['role_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => bcrypt($data['password']),
            'first_name' => $data['first_name'],
             'lat_name' => $data['last_name']
        ]);

        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }
}
