<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* wag delete baka magamit */
        // return parent::toArray($request);
        //customize response
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'role' => $this->role->role_name, // Add role name for frontend
            'name' => $this->name,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'is_active' => $this->is_active,
        ];
    }
}
