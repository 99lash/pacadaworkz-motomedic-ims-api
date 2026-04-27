<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            'id' => $this->id,
            'role' => $this->role_name,
            'description' => $this->description,
            'users_count' => $this->users_count ?? 0,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions'))
        ];
    }


}
