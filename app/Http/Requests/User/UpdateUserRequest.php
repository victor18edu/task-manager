<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|email|unique:users,email|max:200',
            'password' => 'nullable|string|min:8|regex:/[a-z]/i|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'status' => 'required|boolean',
        ];
    }
}
