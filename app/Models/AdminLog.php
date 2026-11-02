<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'admin_logs';

    protected $fillable = [
        // TODO: definisikan kolom yang dapat diisi
    ];

    // TODO: tambahkan relasi Eloquent sesuai ERD
}
