<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Http\Requests\Settings\UpdateSystemSettingRequest;
use App\Services\SystemSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class SystemSettingController extends Controller
{
    protected $systemSettingService;

    public function __construct(SystemSettingService $systemSettingService)
    {
        $this->systemSettingService = $systemSettingService;
    }

    /**
     * Get global system configuration.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $settings = $this->systemSettingService->getAllSettings();

            return response()->json([
                'success' => true,
                'data' => $settings,
            ]);
        } catch (Exception $e) {
            Log::error('System Settings [GET] Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update global system configuration.
     *
     * @param UpdateSystemSettingRequest $request
     * @return JsonResponse
     */
    public function update(UpdateSystemSettingRequest $request): JsonResponse
    {
        try {
            $updatedSettings = $this->systemSettingService->updateSettings($request->validated()['settings']);

            return response()->json([
                'success' => true,
                'message' => 'System settings updated successfully.',
                'data' => $updatedSettings,
            ]);
        } catch (Exception $e) {
            Log::error('System Settings [PATCH] Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
