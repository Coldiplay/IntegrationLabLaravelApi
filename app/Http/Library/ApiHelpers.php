<?php

namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        $container_type = gettype($data);

        $typeName = 'undefined';

        if ($container_type === 'object') {
            $typeName = get_class($data);

            if (str_contains($typeName, '\\')) {
                $typeName = substr($typeName, strrpos($typeName, '\\') + 1);
            }

            if (str_contains($typeName, 'Resource')) {
                $typeName = substr($typeName, 0, strrpos($typeName, 'Resource'));
            } else if (str_contains($typeName, 'Collection')) {
                $typeName = substr($typeName, 0, strrpos($typeName, 'Collection'));
                $container_type = 'array';
            }
        }

        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'container_type' => $container_type,
        ];

        $response['class_type'] = $typeName;

        return response()->json($response, $code);
    }
    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }
}
