<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PBI-04: Manajemen Jasa (Sprint 1)
 * PIC: Anindya Putri Prameswari (102022400026)
 */
class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'provider_id',
        'category_id',
        'judul',
        'deskripsi',
        'harga',
        'durasi',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'durasi' => 'integer',
    ];

    /**
     * Alias accessor agar kompatibel dengan service_id
     */
    public function getServiceIdAttribute(): int
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
     * Relasi ke penyedia jasa (User)
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
     * Relasi ke kategori jasa
     */
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id', 'id');
    }

    /**
     * Relasi ke portofolio hasil karya untuk jasa ini
     */
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'service_id', 'id');
    }

    /**
     * Scope untuk memfilter jasa yang berstatus aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Method untuk mengubah status (aktifkan / nonaktifkan)
     * Sesuai operasi 'aktifkan()' pada Class Diagram (Gambar 4.4.2)
     */
    public function toggleStatus(): bool
    {
        $this->status = ($this->status === 'aktif') ? 'nonaktif' : 'aktif';
        return $this->save();
    }

    /**
     * Alias operasi 'aktifkan()' sesuai spesifikasi Class Diagram (Gambar 4.4.2)
     */
    public function aktifkan(): bool
    {
        return $this->toggleStatus();
    }

    /**
     * Helper untuk memformat harga rupiah (mis. Rp20.000)
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp' . number_format((float) $this->harga, 0, ',', '.');
    }
}
