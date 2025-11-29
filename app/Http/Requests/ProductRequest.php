<?php

namespace App\Http\Requests;

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
             'category_id' => 'required',
             'brand_id' => 'required',
             'sku' => 'unique:products,sku,'.$id.'|required',
             'name' => 'required',
             'description' => 'sometimes',
             'unit_price' => 'required',
             'cost_price' => 'required',
             'reorder_level' => 'sometimes',
             'image_url' => 'sometimes',
        ];
    }
}
