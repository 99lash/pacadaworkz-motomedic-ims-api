<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use App\Services\PermissionService;
class PermissionController extends Controller
{
    //

    private $permissionService;


    public function __construct(PermissionService $permissionService){
        $this->permissionService = $permissionService;
    }

    
    public function index(){
      
        $permissions = $this->permissionService->getAllPermissions();

        return response()->json(
            [
                'success' => true,
                 'data' => PermissionResource::collection($permissions)
            ]
        );

    }
}
