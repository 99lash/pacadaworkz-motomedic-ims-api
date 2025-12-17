<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrdersRequest extends FormRequest
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
            'supplier_id' => 'required',
            'user_id' => 'required',
            'order_date' => 'required|date',
            'expected_delivery' =>'nullable|date',
            'total_amount' => 'required',
            'status' => 'nullable',
             'notes' => 'nullable'
        ];
    }
}
