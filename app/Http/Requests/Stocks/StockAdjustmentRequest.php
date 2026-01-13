<?php

namespace App\Http\Requests\Stocks;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'product_id' => 'required|integer|exists:products,id',
                'reason' => 'required|string|in:damaged,refunded',
                'quantity' => 'required|integer|not_in:0',
                'notes' => 'nullable|string',
            ];
        }

        return [
            'reason' => 'sometimes|string|in:damaged,refunded',
            'notes' => 'nullable|string',
        ];
    }
}
