<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RolesPermissionRequest; 
use App\Services\RolePermissionService; 
use App\Http\Resources\RolePermissionResource;

class RolePermissionController extends Controller
{
    protected $rolePermissionService;

    public function __construct(RolePermissionService $rolePermissionService)
    {
        $this->rolePermissionService = $rolePermissionService;
    }

    /**
     * Assign permissions to a role.
     *
     * @param  RolesPermissionRequest  $request
     * @param  int  $id  Role ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignPermissions(RolesPermissionRequest $request, $id)
    {
        // Pass validated permissions to service layer
        $result = $this->rolePermissionService->assignPermissions($id, $request->permissions);

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 404);
        }

        // Wrap permissions in resource
        $permissionsResource = RolePermissionResource::collection($result['permissions']);

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'role_name' => $result['role_name'],
                'permissions' => $permissionsResource
            ]
        ], 200);
    }
}
