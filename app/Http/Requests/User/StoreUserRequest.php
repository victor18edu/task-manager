<?php

namespace App\Http\Requests\User;

class StoreUserRequest extends AbstractUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function emailRule(): array
    {
        return ['required', 'email', 'max:200', 'unique:users,email'];
    }

    protected function passwordRule(): array
    {
        return ['required', 'string', 'min:8', 'regex:/[a-z]/i', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'];
    }

    protected function statusRule(): string
    {
        return 'nullable|boolean';
    }
}
