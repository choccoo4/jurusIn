<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(array $data, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            ...$data,
        ], $status);
    }

    protected function errorResponse(string $message, int $status = 400, array $extra = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
            ...$extra
        ], $status);
    }
}
