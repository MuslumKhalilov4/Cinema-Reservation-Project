<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    public function success(mixed $data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => __($message),
        ];

        $data !== null && $response['data'] = $data;

        return response()->json($response, $status);
    }

    public function paginated(ResourceCollection $resource, string $message = 'Success', int $status = 200): JsonResponse
    {
        return $resource->additional([
            'success' => true,
            'message' => __($message),
        ])->response()->setStatusCode($status);
    }

    public function error(string $message = 'Error', int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}