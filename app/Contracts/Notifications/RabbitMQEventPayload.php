<?php

namespace App\Contracts\Notifications;

interface RabbitMQEventPayload
{
    /**
     * @param string $action  'created' | 'updated' | 'deleted'
     * @return array
     */
    public function toEventPayload(string $action): array;
}
