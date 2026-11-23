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
            return $query->where(function ($q) use ($keyword) {
                $q->where('nama_kategori', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }

    /** Ikon Material Symbols untuk kategori (desain PBI-05), ditebak dari namanya. */
    public function getIconAttribute(): string
    {
        $name = mb_strtolower($this->nama_kategori ?? '');
        $map = [
            'desain' => 'palette', 'tutor' => 'school', 'akademik' => 'school', 'video' => 'movie',
            'animasi' => 'movie', 'web' => 'language', 'teknologi' => 'language', 'data' => 'table_chart',
            'office' => 'description', 'dokumen' => 'description', 'foto' => 'photo_camera', 'media' => 'photo_camera',
            'tulis' => 'edit_note', 'musik' => 'music_note', 'terjemah' => 'translate',
        ];
        foreach ($map as $key => $icon) {
            if (str_contains($name, $key)) {
                return $icon;
            }
        }
        return 'category';
    }
}
