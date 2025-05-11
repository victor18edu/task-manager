<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends AbstractUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function emailRule(): array
    {
        $user = $this->route('user');
        return ['required', 'email', 'max:200', Rule::unique('users')->ignore($user)];
    }

    protected function passwordRule(): array
    {
        return ['nullable', 'string', 'min:8', 'regex:/[a-z]/i', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'];
    }

    protected function statusRule(): string
    {
        return 'required|boolean';
    }
}
