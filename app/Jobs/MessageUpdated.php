<?php

namespace App\Jobs;

use App\Models\Message;
use App\Observers\RabbitMQSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageUpdated extends RabbitMQSender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $messageId, public ?string $correlation_id = null)
    {
        parent::__construct('Message', $this->correlation_id);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $message = Message::query()
            //->with('attachments')
            ->findOrFail($this->messageId);

        $payload = [
            'id' => (string) $message->id,
            'chat_id' => (string) $message->chat_id,
            'user_id' => (string) $message->sender_id,
            'action' => 'updated',
            'content' => $message->content,
            'updated_at' => optional($message->updated_at)->utc()?->toIso8601String(),
            'version' => $message->version ?? null,
        ];

        $this->sendToQueue($this->messageId, $payload);
    }
}
