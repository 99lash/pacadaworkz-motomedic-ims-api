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
            'id' => $this->id,
            'sku' => $this->whenLoaded('product', function () {
                return $this->product->sku;
            }),
            'product_name' => $this->whenLoaded('product', function () {
                return $this->product->name;
            }),
            'category' => $this->whenLoaded('product', function () {
                return $this->product->category->name;
            }),
            'brand' => $this->whenLoaded('product', function () {
                return $this->product->brand->name;
            }),
            'quantity' => $this->quantity,
            'last_stock_in' => $this->last_stock_in,
        ];
    }
}
