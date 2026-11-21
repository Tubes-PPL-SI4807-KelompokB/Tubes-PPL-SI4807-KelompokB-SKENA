<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PBI-06: Manajemen Portfolio (Sprint 2)
 * PIC: Anindya Putri Prameswari (102022400026)
 */
class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolios';

    protected $fillable = [
        'provider_id',
        'service_id',
        'judul',
        'deskripsi',
        'berkas_tautan',
        'status_tampil',
    ];

    protected $casts = [
        'status_tampil' => 'boolean',
    ];

    /**
     * Alias accessor agar kompatibel dengan portfolio_id
     */
    public function getPortfolioIdAttribute(): int
    {
        return $this->id;
    }

    /**
     * Alias accessor agar kompatibel dengan user_id
     */
    public function getUserIdAttribute(): int
    {
        return $this->provider_id;
    }

    public function setUserIdAttribute($value): void
    {
        $this->attributes['provider_id'] = $value;
    }

    /**
     * Relasi ke penyedia karya (User)
     */
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }

    /**
     * Relasi alias ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }

    /**
     * Relasi ke jasa terkait (Service)
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    /**
     * Scope untuk hanya mengambil karya yang status tampil = true
     */
    public function scopeVisible($query)
    {
        return $query->where('status_tampil', true);
    }

    /**
     * Method untuk toggle visibilitas karya
     */
    public function toggleVisibility(): bool
    {
        $this->status_tampil = !$this->status_tampil;
        return $this->save();
    }

    /**
     * Helper untuk menentukan tipe media (Gambar, PDF, Tautan Video/Drive)
     */
    public function getMediaTypeAttribute(): string
    {
        if (empty($this->berkas_tautan)) {
            return 'Lainnya';
        }

        $val = strtolower($this->berkas_tautan);
        if (str_contains($val, 'youtube.com') || str_contains($val, 'youtu.be') || str_ends_with($val, '.mp4')) {
            return 'Tautan video';
        }
        if (str_ends_with($val, '.pdf')) {
            return 'Berkas PDF';
        }
        if (str_contains($val, 'drive.google.com')) {
            return 'Google Drive';
        }
        if (preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/', $val)) {
            return 'Gambar karya';
        }

        return 'Tangkapan layar';
    }
}
