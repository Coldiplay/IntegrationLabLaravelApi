<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationAttempt extends Model
{
    protected $fillable = [
        'notification_id', 'attempt_no', 'request_metadata', 'response_status', 'response_body', 'duration_ms'
    ];

    protected $casts = [
        'request_metadata' => 'array',
        'response_body' => 'array',
    ];
}
