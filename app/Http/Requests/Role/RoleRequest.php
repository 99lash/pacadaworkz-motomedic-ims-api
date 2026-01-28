<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role_name' => [
                'required',
                Rule::unique('roles', 'role_name')->ignore($this->route('id')),
                'max:50'
            ],
            'description' => 'required',
        ];
    }
}
