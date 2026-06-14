<?php

namespace App\Services\Notifications;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class RabbitMQPublisher
{
    /**
     * @param string $modelClass Полное имя класса модели (для формирования exchange/routing)
     * @param string $action created | updated | deleted
     * @param array $payload Данные от модели
     * @param string|null $correlationId
     * @throws Throwable
     */
    public function publish(string $modelClass, string $action, array $payload, ?string $correlationId = null): void
    {
        // Преобразуем App\Models\Message → message
        $modelName = class_basename($modelClass);
        $lowerModel = Str::lower($modelName);

        $event = [
            'envelope' => [
                'type'          => $modelName . Str::ucfirst($action),  // MessageCreated, MessageUpdated, MessageDeleted
                'version'       => 'v1',
                'eventId'       => (string) Str::uuid7(),
                'occurredAt'    => now()->utc()->toIso8601String(),
                'correlationId' => $correlationId ?? (string) Str::uuid7(),
                'producer'      => config('app.name', 'api.laravel'),
            ],
            'payload' => $payload,
        ];

        $exchange    = 'notifications.' . $lowerModel . 's';   // notifications.messages
        $routingKey  = $lowerModel . '.' . $action;           // message.created

        $properties = [
            'content_type'     => 'application/json',
            'content_encoding' => 'utf-8',
            'delivery_mode'    => 2,
            'message_id'       => $event['envelope']['eventId'],
            'type'             => $event['envelope']['type'],
            'app_id'           => config('app.name', 'api.laravel'),
            'timestamp'        => now()->utc()->getTimestamp(),
            'correlation_id'   => $event['envelope']['correlationId'],
            'headers'          => [
                'schema'        => $modelName . 'Event',
                'schemaVersion' => $event['envelope']['version'],
            ],
        ];

        $json = json_encode($event, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            /*Queue::connection('rabbitmq')->getChannel()
                ->basic_publish(
                    new AMQPMessage($json, $properties),
                $exchange,
                $routingKey);*/
            Queue::connection('rabbitmq')->pushRaw(
                $json,
                'notifications.signalr',
                [
                    'exchange'    => $exchange,
                    'routing_key' => $routingKey,
                    'properties'  => $properties,
                    'queue'       => 'notifications.signalr',
                ]
            );

            Log::info('Published event to RabbitMQ', [
                'routing_key'  => $routingKey,
                'exchange'     => $exchange,
                'model'        => $modelName,
                'action'       => $action,
                'eventId'      => $event['envelope']['eventId'],
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to publish event to RabbitMQ', [
                'routing_key' => $routingKey,
                'exchange'    => $exchange,
                'model'       => $modelName,
                'action'      => $action,
                'error'       => $e->getMessage(),
            ]);
            throw $e; // пробрасываем для ретраев
        }
    }
}
