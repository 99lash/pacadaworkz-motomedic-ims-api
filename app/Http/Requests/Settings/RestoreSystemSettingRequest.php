<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class RestoreSystemSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Middleware handles role authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'backup_file' => [
                'required',
                'file',
                // allowing common extensions for pg_dump custom format or plain text
                'mimes:sql,gz,tar,dump,bin',
                'max:102400', // Limit to 100MB (adjust as needed)
            ],
        ];
    }
}
