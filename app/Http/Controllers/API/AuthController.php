<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $service;
    public function __construct(Authservice $service){
        $this->service = $service;
        $this->middleware('auth:sanctum')->except(['login', 'loginGoogle', 'register']);
    }

    public function login (Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $result = $this->service->login($validated);

        if(!$result) {
            return response()->json(['message' => 'invalid credentials'], 401);
        }

        $cookie = cookie()->make('refresh_token', $result['refresh_token'], 60*24*7, null, null, false, true);

        return response()->json([
            'user' => $result['user'],
            'access_token' => $result['access_token'],
            'refresh_token' => 'httpOnlyCookie',
        ])->withCookie($cookie);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
        ]);

        $user = $this->service->register($validated);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
}
