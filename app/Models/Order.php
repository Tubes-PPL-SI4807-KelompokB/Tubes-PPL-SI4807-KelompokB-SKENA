<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'harga_final' => 'decimal:2',
        ];
    }

    // Relasi ke jasa yang dipesan
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Relasi ke pembeli (dipertahankan dari main)
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    // Alias untuk pengguna yang mengajukan pesanan
    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    // Relasi pembayaran (dipertahankan dari PBI-13)
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    // Relasi voucher
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    // Relasi ke request jasa
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    // Riwayat negosiasi harga
    public function negotiations(): HasMany
    {
        return $this->hasMany(Negotiation::class, 'order_id');
    }
}
