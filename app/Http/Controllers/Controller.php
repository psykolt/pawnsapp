<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * @param array|null $data
     * @return JsonResponse
     */
    public function success(?array $data = null): JsonResponse
    {
        return response()->json(['data' => $data]);
    }

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function error(string $message, int $code = 400): JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }
}
