<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * TIM-F: hak akses berbentuk "{modul}.{aksi}" (9 modul x 4 aksi = 36 izin).
     * modul => [label, ikon Material Symbols]
     */
    public const MODULES = [
        'profil'     => ['Profil', 'person'],
        'jasa'       => ['Jasa', 'storefront'],
        'kategori'   => ['Kategori Jasa', 'category'],
        'voucher'    => ['Voucher', 'confirmation_number'],
        'request'    => ['Request Jasa', 'assignment'],
        'pesanan'    => ['Pesanan', 'receipt_long'],
        'pembayaran' => ['Pembayaran', 'payments'],
        'notifikasi' => ['Notifikasi', 'notifications'],
        'pengguna'   => ['Pengguna', 'group'],
    ];

    public const ACTIONS = [
        'lihat'  => 'Lihat',
        'tambah' => 'Tambah',
        'ubah'   => 'Ubah',
        'hapus'  => 'Hapus',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
