<?php

namespace App\Services\Notifications;

use App\Models\NotificationTemplate;
use RuntimeException;

class MessageBuilder
{
    public function build(string $templateKey, string $locale, array $data): string
    {
        $tpl = NotificationTemplate::query()
            ->where('key', $templateKey)
            ->where('channel', 'sms')
            ->where('locale', $locale)
            ->where('is_active', true)
            ->orderByDesc('version')
            ->first();

        if (!$tpl) {
            throw new RuntimeException("Template not found: {$templateKey}/{$locale}");
        }

        $body = $tpl->body;
        foreach ($data as $key => $val) {
            $body = str_replace(['{{' . $key . '}}', '{{ ' . $key . ' }}'], (string) $val, $body);
        }

        // Basic length guard; fine-tune per GSM/Unicode if needed
        return mb_substr($body, 0, 1000);
    }
}
