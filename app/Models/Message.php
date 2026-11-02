<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        // TODO: definisikan kolom yang dapat diisi
    ];

    // TODO: tambahkan relasi Eloquent sesuai ERD
}
