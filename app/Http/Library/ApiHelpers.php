<?php

namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function onSuccess($data, string $message = '', int $code = 200, ?string $className = null, ?string $container_type = null, bool $checkContainerType = true)
    : JsonResponse
    {
        if (empty($container_type)) $container_type = gettype($data);

        if (empty($className)) $className = 'undefined';

        if ($checkContainerType) {
            if ($container_type == 'array') {
                $className = get_class($data[0]);
            } else if (empty($data)) {
                $className = 'null';
                $container_type = 'object';
            } else{
                $className = get_class($data);
            }

            if (str_contains($className, '\\')) {
                $className = substr($className, strrpos($className, '\\') + 1);
            }

            if (str_contains($className, 'Resource')) {
                $className = substr($className, 0, strrpos($className, 'Resource'));
            } else if (str_contains($className, 'Collection')) {
                $className = substr($className, 0, strrpos($className, 'Collection'));
                $container_type = 'array';
            }
        }

        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'container_type' => $container_type,
        ];

        $response['class_type'] = $className;

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
