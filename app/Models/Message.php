<?php

namespace App\Models;

use App\Contracts\Notifications\RabbitMQEventPayload;
use App\Observers\MessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([MessageObserver::class])]
class Message extends Model implements RabbitMQEventPayload
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function sender() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chat() : BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function toEventPayload(string $action): array
    {
        // Базовые поля, общие для всех действий
        $payload = [
            'id'      => (string) $this->id,
            'chat_id' => (string) $this->chat_id,
            'user_id' => (string) $this->sender_id,
            'action'  => $action,
        ];

        if ($action === 'deleted') {
            // Для удаления передаём минимум
            $payload['deleted_at'] = optional($this->deleted_at)->utc()?->toIso8601String();
            return $payload;
        }

        // created / updated
        $payload['content']    = $this->content;
        $payload['version']    = $this->version ?? null;
        $payload['updated_at'] = optional($this->updated_at)->utc()?->toIso8601String();

        return $payload;
    }
}
