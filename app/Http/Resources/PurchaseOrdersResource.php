<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'supplier' => $this->supplier->name,
            'user' => $this->user->name,
            'order_date' => $this->order_date,
            'expected_delivery' => $this->expected_delivery,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
             'notes' => $this->notes

        ];
    }
}
