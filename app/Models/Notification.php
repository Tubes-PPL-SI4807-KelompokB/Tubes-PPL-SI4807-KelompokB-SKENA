<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Notification – PBI-17 (PIC: Aprilia)
 * Status dibaca: `is_read` (boolean) + `read_at` (waktu dibaca), selalu diisi bersamaan.
 */
class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Halaman tujuan notifikasi berdasarkan isi kolom data (null bila tidak ada).
     * Dipakai lonceng di header dan tombol "Lihat" di halaman notifikasi.
     */
    public function getUrlAttribute(): ?string
    {
        $data = $this->data ?? [];

        return match (true) {
            isset($data['payment_id'])         => route('payments.show', $data['payment_id']),
            isset($data['order_id'])           => route('orders.show', $data['order_id']),
            isset($data['service_request_id']) => route('orders.index', ['peran' => 'penyedia', 'status' => 'baru']),
            isset($data['request_id'])         => route('requests.index'),
            default                            => null,
        };
    }

    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }
}
