<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       // return parent::toArray($request);


       return [
        'company' => $this->name,
        'contact_person' => $this->contact_person,
        'email' => $this->email,
        'phone' => $this->phone,
        'address' => $this->address
       ];
    }
}
