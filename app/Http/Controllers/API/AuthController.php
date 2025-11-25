<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $tokens = $this->authService->login($credentials);
         
        if (!$tokens) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'access_token' => $tokens['access_token'],
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'token_type' => 'bearer',
            'refresh_token' => $tokens['refresh_token']
             
        ]);
    }
    
  
    public function refresh(){
        $refresh = $this->authService->refresh();
          return response()->json($refresh);
    }
  
    public function logout(Request $request)
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json($this->authService->me());
    }
  
    public function test(){
        return response()->json(['message'=>'success']);
    }

}
