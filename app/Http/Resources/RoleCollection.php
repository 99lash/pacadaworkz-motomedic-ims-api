<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ 
             'success' => true,
            'data' => $this->collection->map(function ($role){
                return [
                    'id' => $role->id,
                     'role_name' => $role->role_name,
                     'description' => $role->description
                ];
            })
        ];
    }

      
      
}
