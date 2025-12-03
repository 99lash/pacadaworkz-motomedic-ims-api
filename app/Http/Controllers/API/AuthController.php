<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\RefreshTokenResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Login user and return tokens
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $tokens = $this->authService->login($credentials);

            return response()->json([
                'success' => true,
                'data' => new LoginResource($tokens),
            ], 200);
        } catch (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Refresh access token
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $tokens = $this->authService->refresh();

            return response()->json([
                'success' => true,
                'data' => new RefreshTokenResource($tokens),
            ], 200);
        } catch (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Successfully logged out',
                ],
            ], 200);
        } catch (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Get authenticated user
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->me();

            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
            ], 200);
        } catch (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}
