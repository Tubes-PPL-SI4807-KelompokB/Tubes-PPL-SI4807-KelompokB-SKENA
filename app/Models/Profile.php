<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'avatar',
        'banner',
        'city',
        'website',
        'instagram',
        'linkedin',
        'github',
        'hourly_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
