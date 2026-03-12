<?php

// app/Traits/ApiResponse.php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Return a standardized success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data = [], string $message = 'Success', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return a standardized error response.
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @return JsonResponse
     */
    protected function error(string $message = 'An error occurred', int $code = Response::HTTP_BAD_REQUEST, $error = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $error,
        ], $code);
    }
}
