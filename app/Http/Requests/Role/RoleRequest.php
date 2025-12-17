<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role_name' => 'required|unique:roles,role_name,' . ($this->role ? $this->role->id : '') . '|max:50',
            'description' => 'required',
        ];
    }
}
