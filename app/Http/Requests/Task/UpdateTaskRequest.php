<?php

namespace App\Http\Requests\Task;

class UpdateTaskRequest extends AbstractTaskRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
