<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkProgress extends Model
{
    use HasFactory;

    protected $table = 'work_progress';

    protected $fillable = [
        // TODO: definisikan kolom yang dapat diisi
    ];

    // TODO: tambahkan relasi Eloquent sesuai ERD
}
