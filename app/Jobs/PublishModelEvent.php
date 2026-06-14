<?php

namespace App\Jobs;

use App\Contracts\Notifications\RabbitMQEventPayload;
use App\Services\Notifications\RabbitMQPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class PublishModelEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string           $modelClass,
        private readonly array|string|int $key,
        private readonly string           $action,
        private readonly ?string          $correlationId = null
    ) {}

    public function handle(RabbitMQPublisher $publisher): void
    {
        // Для deleted ищем даже удалённую запись (если используется SoftDeletes)
        $query = $this->action === 'deleted'
            ? $this->modelClass::withTrashed()
            : $this->modelClass::query();

        if (is_array($this->key)) {
            foreach ($this->key as $column => $value) {
                $query->where($column, $value);
            }
            $model = $query->firstOrFail();

        } else{
            $model = $query->findOrFail($this->key);
        }


        if (!$model instanceof RabbitMQEventPayload) {
            throw new RuntimeException("Model {$this->modelClass} must implement RabbitMQEventPayload");
        }

        $payload = $model->toEventPayload($this->action);

        $publisher->publish($this->modelClass, $this->action, $payload, $this->correlationId);
    }
}
