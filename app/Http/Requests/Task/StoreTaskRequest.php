<?php

namespace App\Http\Requests\Task;

class StoreTaskRequest extends AbstractTaskRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
