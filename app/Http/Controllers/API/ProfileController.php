<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Auth\UserNotFoundException;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\UserService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the specified resource.
     */
    public function showProfile()
    {
        $userId = Auth::id();

        try {
            $response = $this->userService->getUserById($userId);

            return response()->json([
                'success' => true,
                'data' => ProfileResource::make($response),
                'message' => 'User profile retrieved succesfully'
            ]);
        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        } catch (\Exception $e) {
            \Log::error('Settings Profile [GET] Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $userId = Auth::id();
        try {
            $response = $this->userService->updateUserById($userId, $request->validated());

            return response()->json([
                'success' => true,
                'data' => $response,
                'message' => 'User profile updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Settings Profile [PATCH] Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
