<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Str;
use Throwable;

abstract class RabbitMQSender
{
    public function __construct(public string $model, public ?string $correlation_id = null)
    {}

    public function sendToQueue($modelId, array $payload) : void
    {
        $event = [
            'envelope' => [
                'type' => $this->model . 'Updated',
                'version' => 'v1',
                'eventId' => (string) Str::uuid7(),
                'occurredAt' => now()->utc()->toIso8601String(),
                'correlationId' => $this->correlationId ?? (string) Str::uuid7(),
                'producer' => config('app.name', 'api.laravel'),
            ],
            'payload' => $payload
        ];

        $lowerModel = Str::lower($this->model);
        $exchange = 'notifications.' . $lowerModel . 's';
        $routingKey =  $lowerModel. '.updated';

        $properties = [
            'content_type'    => 'application/json',
            'content_encoding'=> 'utf-8',
            'delivery_mode'   => 2, // persistent
            'message_id'      => $event['envelope']['eventId'],
            'type'            => $event['envelope']['type'],
            'app_id'          => config('app.name', 'api.laravel'),
            'timestamp'       => now()->utc()->getTimestamp(),
            'correlation_id'  => $event['envelope']['correlationId'],
            // Кастомные заголовки (удобно для логирования/фильтрации)
            'headers'         => [
                'schema'        => $this->model . 'Event',
                'schemaVersion' => $event['envelope']['version'],
            ],
        ];

        $json = json_encode($event, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            // В качестве второго аргумента (queue) передаём null — мы публикуем в exchange с routing_key.
            Queue::connection('rabbitmq')->pushRaw(
                $json,
                null,
                [
                    'exchange'    => $exchange,
                    'routing_key' => $routingKey,
                    'properties'  => $properties,
                    // при необходимости можно указать конкретную очередь:
                    // 'queue'    => 'notifications.signalr',
                ]
            );

            Log::info('Published event to RabbitMQ', [
                'routing_key' => $routingKey,
                'exchange' => $exchange,
                'modelName' => $this->model,
                'modelId' => $modelId,
                'eventId' => $event['envelope']['eventId'],
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to publish event to RabbitMQ', [
                'routing_key' => $routingKey,
                'exchange' => $exchange,
                'modelName' => $this->model,
                'modelId' => $modelId,
                'error' => $e->getMessage(),
            ]);

            // Пробрасываем исключение, чтобы джоб ушёл в ретрай по стандартной политике Laravel
            throw $e;
        }
    }
}
