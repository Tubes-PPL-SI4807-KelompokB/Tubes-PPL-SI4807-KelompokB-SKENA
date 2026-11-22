<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    const STATUS_MENUNGGU_KONFIRMASI = 'menunggu_konfirmasi';
    const STATUS_LUNAS               = 'lunas';
    const STATUS_DITOLAK             = 'ditolak';

    protected $fillable = [
        'order_id',
        'payer_id',
        'payee_id',
        'amount',
        'status',
        'proof_path',
        'proof_original_name',
        'proof_size',
        'proof_uploaded_at',
        'confirmed_at',
        'rejection_note',
    ];

    protected $casts = [
        'proof_uploaded_at' => 'datetime',
        'confirmed_at'      => 'datetime',
        'amount'            => 'integer',
        'proof_size'        => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }

    public function isEditable(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_KONFIRMASI;
    }

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_LUNAS;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
            self::STATUS_MENUNGGU_KONFIRMASI => 'Menunggu Konfirmasi',
            self::STATUS_LUNAS               => 'Lunas',
            self::STATUS_DITOLAK             => 'Ditolak',
            default                          => ucfirst($this->status),
        };
    }

    public function amountFormatted(): string
    {
        return 'Rp' . number_format($this->amount, 0, ',', '.');
    }

    public function proofSizeFormatted(): string
    {
        if (!$this->proof_size) return '';
        if ($this->proof_size >= 1_048_576) {
            return number_format($this->proof_size / 1_048_576, 1) . ' MB';
        }
        return number_format($this->proof_size / 1_024, 0) . ' KB';
    }
}
