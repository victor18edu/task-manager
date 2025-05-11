<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Retorna uma resposta de sucesso com dados
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function success($data = null, $message = 'Operação realizada com sucesso!', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Retorna uma resposta de erro
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  mixed  $data
     * @return JsonResponse
     */
    public function error($message = 'Erro inesperado', $statusCode = 400, $data = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Retorna uma resposta de validação com erros
     *
     * @param  mixed  $errors
     * @param  string  $message
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function validationError($errors, $message = 'Dados inválidos', $statusCode = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
