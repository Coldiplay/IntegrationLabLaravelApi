<?php

namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        $type = gettype($data);
        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'phpType' => $type,
        ];

        $typeName = 'undefined';

        if ($type === 'object') {
            $typeName = get_class($data);

            if (str_contains($typeName, '\\')) {
                $typeName = substr($typeName, strrpos($typeName, '\\') + 1);
            }

            if (str_contains($typeName, 'Resource')) {
                $typeName = substr($typeName, 0, strrpos($typeName, 'Resource'));
            }

            if (str_contains($typeName, 'Collection')) {
                $typeName = substr($typeName, 0, strrpos($typeName, 'Collection'));
            }
        }

        $response['type'] = $typeName;

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
