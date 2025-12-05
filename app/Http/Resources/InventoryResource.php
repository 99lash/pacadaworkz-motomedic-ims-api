<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
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
           'sku' => $this->product->sku,
           'product_name' => $this->product->name,
            'category' => $this->product->category_id,
            'brand' => $this->product->brand_id,
             'quantity' => $this->quantity,
             'last_stock_in' => $this->last_stock_in,
             
        ];
    }
}
