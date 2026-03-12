<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta ?: null,
        ], $status);
    }

    protected function error(
        string $message = 'Error',
        int $status = Response::HTTP_BAD_REQUEST,
        mixed $errors = null
    ): JsonResponse {

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}