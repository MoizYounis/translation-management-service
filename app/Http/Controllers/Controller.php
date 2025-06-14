<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Translation Service API",
 *      description="API documentation for Translation Service"
 * )
 */

abstract class Controller
{
    public function sendJson($status, $message, $data = [], $status_code = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status_code);
    }
}
