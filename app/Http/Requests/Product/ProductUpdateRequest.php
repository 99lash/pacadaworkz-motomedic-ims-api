<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
        return [
             'category_id' => 'required',
             'brand_id' => 'required',
             'name' => 'required',
             'description' => 'sometimes',
             'unit_price' => 'required',
             'cost_price' => 'required',
             'reorder_level' => 'sometimes',
             'image_url' => 'sometimes',
        ];
    }
}
