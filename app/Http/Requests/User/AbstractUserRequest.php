<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractUserRequest extends FormRequest
{
    abstract protected function emailRule(): array|string;
    abstract protected function passwordRule(): array|string;
    abstract protected function statusRule(): array|string;

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:200',
            'email' => $this->emailRule(),
            'password' => $this->passwordRule(),
            'status' => $this->statusRule(),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo :min caracteres.',
            'name.max' => 'O nome deve ter no máximo :max caracteres.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'email.max' => 'O e-mail deve ter no máximo :max caracteres.',

            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser uma cadeia de caracteres.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.regex' => 'A senha deve conter pelo menos uma letra, um número e um caractere especial.',
            'password.confirmed' => 'A confirmação da senha não confere.',

            'status.boolean' => 'O campo status deve ser verdadeiro ou falso.',
        ];
    }
}
