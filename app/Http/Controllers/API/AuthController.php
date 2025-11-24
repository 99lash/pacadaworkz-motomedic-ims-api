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
        $token = $this->authService->login($credentials);

        if (!$token) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    
   public function register(Request $request){
    $validated = $request->validate([
        'role_id' => 'required',
        'name' => 'required|string|max:50',
        'email' => 'required|email|unique:users,email',
        'first_name' => 'required|string|max:50',
        'last_name' => 'required|string|max:50',
        'password' => 'required|string|min:6'
    ]);

    $register = $this->authService->register($validated);

    return response()->json([
        'message' => 'Successfully registered',
        'data' => $register
    ]); 
}

    public function logout()
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
