<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $id = $this->route('id');
        return [
             'category_id' => 'required|exists:categories,id',
             'brand_id' => 'required|exists:brands,id',
             'sku' => 'required|unique:products,sku',
             'name' => 'required',
             'description' => 'sometimes|nullable',
             'unit_price' => 'required|numeric',
             'cost_price' => 'required|numeric',
             'reorder_level' => 'sometimes|integer',
             'image_url' => 'sometimes|nullable',
             'initial_stock' => 'sometimes|integer|min:0',
             'location' => 'sometimes|nullable|string',
        ];
    }
}
