<?php

namespace App\Services;

use App\Models\Notification;

/**
 * NotificationService – PBI-17
 * Satu-satunya service lintas tabel untuk notifikasi otomatis.
 */
class NotificationService
{
    public function send(int $userId, string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'data'    => $data ?: null,
            'is_read' => false,
        ]);
    }
}
