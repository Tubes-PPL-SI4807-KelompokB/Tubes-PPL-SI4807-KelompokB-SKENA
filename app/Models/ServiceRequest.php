<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'service_requests';

    /** Status request (nilai tersimpan di kolom status). */
    public const PENDING   = 'pending';
    public const ACCEPTED  = 'accepted';
    public const REJECTED  = 'rejected';
    public const CANCELLED = 'cancelled';

    /** Label tampilan sesuai desain PBI-10: nilai => label */
    public const LABELS = [
        self::PENDING   => 'Menunggu',
        self::ACCEPTED  => 'Diterima',
        self::REJECTED  => 'Ditolak',
        self::CANCELLED => 'Dibatalkan',
    ];

    protected $fillable = [
        'seeker_id',
        'service_id',
        'kebutuhan',
        'tanggal',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }
}
