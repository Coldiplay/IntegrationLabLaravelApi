<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'recipient_phone', 'channel', 'template_key', 'template_version', 'locale', 'payload', 'rendered_body',
        'status', 'scheduled_at', 'sent_at', 'delivered_at', 'attempts_count', 'external_message_id',
        'correlation_id', 'idempotency_key', 'error_code', 'error_message'
    ];

    protected $casts = [
        'payload' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];
}
