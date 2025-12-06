<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'contact_person' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];

        if ($this->isMethod('POST')) {
            // Rules for creating a supplier
            $rules['name'][] = Rule::unique('suppliers', 'name');
            $rules['email'][] = Rule::unique('suppliers', 'email');
        } else if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Rules for updating a supplier
            $supplierId = $this->route('supplier')->id; // Assuming route model binding is used and parameter is 'supplier'
            $rules['name'][] = Rule::unique('suppliers', 'name')->ignore($supplierId);
            $rules['email'][] = Rule::unique('suppliers', 'email')->ignore($supplierId);
        }

        return $rules;
    }
}
