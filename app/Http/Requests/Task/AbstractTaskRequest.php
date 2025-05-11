<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:pending,completed',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.string' => 'O título deve ser uma string.',
            'title.min' => 'O título deve ter no mínimo 3 caracteres.',
            'title.max' => 'O título não pode ter mais que 255 caracteres.',

            'description.string' => 'A descrição deve ser uma string.',
            'description.max' => 'A descrição não pode ter mais que 500 caracteres.',

            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser "pending" (pendente) ou "completed" (concluída).',

            'user_ids.array' => 'A lista de usuários deve ser um array.',
            'user_ids.*.exists' => 'Um ou mais usuários selecionados são inválidos.',
        ];
    }
}
