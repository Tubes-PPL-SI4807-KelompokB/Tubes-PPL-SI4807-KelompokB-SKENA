<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * PBI-11 Manajemen Pesanan (tabel orders).
 *
 * Pesanan dibuat saat penyedia menerima request jasa (PBI-10). harga_final berisi
 * harga jasa saat diterima dan dapat diubah oleh negosiasi (PBI-12); voucher_discount
 * diisi saat pencari menerapkan voucher (PBI-09). Total yang dibayar (PBI-13) dibaca
 * lewat accessor final_price.
 */
class Order extends Model
{
    use HasFactory;

    public const DIKERJAKAN = 'dikerjakan';
    public const REVISI     = 'revisi';
    public const SELESAI    = 'selesai';
    public const DIBATALKAN = 'dibatalkan';

    /** Label tampilan sesuai desain PBI-11. */
    public const LABELS = [
        self::DIKERJAKAN => 'Dikerjakan',
        self::REVISI     => 'Revisi Klien',
        self::SELESAI    => 'Selesai',
        self::DIBATALKAN => 'Dibatalkan',
    ];

    /** Warna badge [latar, teks, titik] sesuai desain PBI-11. */
    public const TONES = [
        self::DIKERJAKAN => ['#E0ECF6', '#346284', '#3F749C'],
        self::REVISI     => ['#FEF3C7', '#B45309', '#F59E0B'],
        self::SELESAI    => ['#DCFCE7', '#047857', '#10B981'],
        self::DIBATALKAN => ['#F1F5F9', '#475569', '#94A3B8'],
    ];

    protected $table = 'orders';

    protected $fillable = [
        'request_id',
        'seeker_id',
        'service_id',
        'voucher_id',
        'voucher_discount',
        'harga_final',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga_final'      => 'decimal:2',
            'voucher_discount' => 'integer',
        ];
    }

    // ── Relasi ───────────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /** Pencari jasa (nama lama dipertahankan agar kode lain tetap jalan). */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function negotiations(): HasMany
    {
        return $this->hasMany(Negotiation::class, 'order_id');
    }

    // ── Scope ────────────────────────────────────────────────

    /** Pesanan yang dibuat pengguna sebagai pencari jasa. */
    public function scopeAsSeeker(Builder $q, int $userId): Builder
    {
        return $q->where('seeker_id', $userId);
    }

    /** Pesanan atas jasa milik pengguna (sebagai penyedia jasa). */
    public function scopeAsProvider(Builder $q, int $userId): Builder
    {
        return $q->whereHas('service', fn ($s) => $s->where('provider_id', $userId));
    }

    // ── Accessor ─────────────────────────────────────────────

    /** Nomor pesanan tampilan, mis. #SKN-0007 (dipakai juga di PBI-13). */
    public function getCodeAttribute(): string
    {
        return '#SKN-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /** Alias seeker_id untuk modul pembayaran (PBI-13). */
    public function getBuyerIdAttribute(): ?int
    {
        return $this->seeker_id;
    }

    /** Harga jasa yang disepakati sebelum potongan voucher. */
    public function getHargaJasaAttribute(): int
    {
        return (int) round((float) ($this->harga_final ?? $this->service?->harga ?? 0));
    }

    /** Total yang harus dibayar = harga jasa − potongan voucher. */
    public function getFinalPriceAttribute(): int
    {
        return max(0, $this->harga_jasa - (int) $this->voucher_discount);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusToneAttribute(): array
    {
        return self::TONES[$this->status] ?? self::TONES[self::DIBATALKAN];
    }

    // ── Aturan akses ─────────────────────────────────────────

    public function isSeeker(User $user): bool
    {
        return (int) $this->seeker_id === (int) $user->id;
    }

    public function isProvider(User $user): bool
    {
        return (int) $this->service?->provider_id === (int) $user->id;
    }

    public function isVisibleTo(User $user): bool
    {
        return $user->isAdmin() || $this->isSeeker($user) || $this->isProvider($user);
    }

    /**
     * Catatan & voucher hanya bisa diubah pencari selama pesanan masih berjalan
     * dan bukti bayar belum dikirim / sudah ditolak (desain p11-2: "sebelum pembayaran").
     */
    public function isEditableBy(User $user): bool
    {
        if (! $this->isSeeker($user) || ! in_array($this->status, [self::DIKERJAKAN, self::REVISI], true)) {
            return false;
        }

        $payment = $this->payment;

        return ! $payment || in_array($payment->status, [
            Payment::STATUS_MENUNGGU_PEMBAYARAN,
            Payment::STATUS_DITOLAK,
        ], true);
    }
}
