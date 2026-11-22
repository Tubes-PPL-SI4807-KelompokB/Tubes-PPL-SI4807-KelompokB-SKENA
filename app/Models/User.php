<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'status',
        'student_verification_status',
        'nim',
        'university',
        'faculty',
        'major',
        'angkatan',
        'ktm_path',
        'verification_notes',
        'verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel roles
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi ke profil pengguna
     */
    public function experiences()
    {
        return $this->hasMany(Experience::class)->latest();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function userNotifications()
    {
    return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Helper cek role
     */
    public function hasRole(string|array $roles): bool
    {
        if (!$this->role) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($this->role->name, $roles);
        }

        return $this->role->name === $roles;
    }

    /**
     * Helper cek permission
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        // Admin memiliki semua hak akses
        if ($this->isAdmin()) {
            return true;
        }

        return $this->role->permissions()->where('name', $permission)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isFreelancer(): bool
    {
        return $this->role?->name === 'freelancer';
    }

    public function isClient(): bool
    {
        return $this->role?->name === 'client';
    }

    public function isVerifiedStudent(): bool
    {
        return $this->student_verification_status === 'verified';
    }

    /**
     * URL Avatar pengguna
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile && $this->profile->avatar) {
            return asset('storage/' . $this->profile->avatar);
        }

        // Fallback placeholder menggunakan inisial nama
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2C99D6&color=ffffff&bold=true';
    }
}
