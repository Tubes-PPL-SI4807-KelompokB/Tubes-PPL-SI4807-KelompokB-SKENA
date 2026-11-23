<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PBI-05: Manajemen Kategori Jasa (Sprint 1)
 * PIC: Anindya Putri Prameswari (102022400026)
 */
class ServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'service_categories';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Alias accessor agar kompatibel dengan category_id
     */
    public function getCategoryIdAttribute(): int
    {
        return $this->id;
    }

    /**
     * Relasi ke Jasa yang ada di dalam kategori ini (1 kategori ke N jasa)
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id', 'id');
    }

    /**
     * Scope untuk pencarian kategori berdasarkan nama / deskripsi
     */
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where('nama_kategori', 'like', "%{$keyword}%")
                         ->orWhere('deskripsi', 'like', "%{$keyword}%");
        }
        return $query;
    }
}
