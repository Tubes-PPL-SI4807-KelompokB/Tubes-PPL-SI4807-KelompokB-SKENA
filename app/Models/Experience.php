<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/** PBI-01 – Riwayat pengalaman & organisasi pengguna */
class Experience extends Model
{
    use HasFactory;

    protected $table = 'experiences';

    public const TYPES = [
        'organisasi'  => 'Organisasi',
        'kerja'       => 'Kerja',
        'kepanitiaan' => 'Kepanitiaan',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'role',
        'start_period',
        'end_period',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** "Sep 2023 – Agu 2024" / "Jan 2024 – sekarang" */
    public function getPeriodAttribute(): string
    {
        return $this->start_period . ' – ' . ($this->end_period ?: 'sekarang');
    }

    /** Ikon Material Symbols sesuai jenis pengalaman (desain PBI-01) */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'kerja'       => 'work',
            'kepanitiaan' => 'event',
            default       => 'groups',
        };
    }
}
