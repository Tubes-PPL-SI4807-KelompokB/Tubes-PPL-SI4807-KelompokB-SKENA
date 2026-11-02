<?php

namespace App\Services;

use App\Models\Notification;

/**
 * NotificationService – PBI-17
 * Satu-satunya service lintas tabel untuk notifikasi otomatis.
 */
class NotificationService
{
    public function send(int $userId, string $type, string $message, ?int $relatedId = null): Notification
    {
        // TODO: kirim notifikasi (database / broadcast)
        return Notification::create([
            'user_id'    => $userId,
            'type'       => $type,
            'message'    => $message,
            'related_id' => $relatedId,
            'is_read'    => false,
        ]);
    }
}
