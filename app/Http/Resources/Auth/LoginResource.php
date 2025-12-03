<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->resource['access_token'],
            'expires_in' => $this->resource['expires_in'],
            'token_type' => $this->resource['token_type'],
            'refresh_token' => $this->resource['refresh_token'],
        ];
    }
}

