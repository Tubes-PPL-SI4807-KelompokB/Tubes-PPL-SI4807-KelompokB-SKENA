<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'service_requests';

    protected $fillable = [
        // TODO: definisikan kolom yang dapat diisi
    ];

    // TODO: tambahkan relasi Eloquent sesuai ERD
}
