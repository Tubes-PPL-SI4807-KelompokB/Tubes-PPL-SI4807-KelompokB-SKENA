<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Roles
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Pengelola sistem, verifikator mahasiswa, dan pemantau transaksi SKENA',
            ],
            [
                'name' => 'freelancer',
                'display_name' => 'Freelancer Mahasiswa',
                'description' => 'Mahasiswa kreatif penyedia jasa (desain, ilustrasi, video, coding, dll.)',
            ],
            [
                'name' => 'client',
                'display_name' => 'Klien / Pemberi Kerja',
                'description' => 'Individu, UMKM, atau organisasi yang mencari dan menyewa jasa kreatif',
            ],
        ];

        $roleModels = [];
        foreach ($roles as $r) {
            $roleModels[$r['name']] = Role::updateOrCreate(['name' => $r['name']], $r);
        }

        // 2. Buat Permissions: matriks modul x aksi (TIM-F), mis. "jasa.ubah"
        $permissionModels = [];
        foreach (Permission::MODULES as $module => [$moduleLabel]) {
            foreach (Permission::ACTIONS as $action => $actionLabel) {
                $name = "{$module}.{$action}";
                $permissionModels[$name] = Permission::updateOrCreate(['name' => $name], [
                    'display_name' => "{$actionLabel} {$moduleLabel}",
                    'description'  => "{$actionLabel} data modul {$moduleLabel}",
                ]);
            }
        }

        // Hapus izin lama yang tidak lagi dipakai (format sebelum matriks modul)
        Permission::whereNotIn('name', array_keys($permissionModels))->delete();

        // 3. Kaitkan Permission ke Role. Urutan aksi: lihat, tambah, ubah, hapus.
        $preset = [
            'admin' => array_fill_keys(array_keys(Permission::MODULES), [1, 1, 1, 1]),
            'freelancer' => [ // Penyedia jasa
                'profil' => [1, 0, 1, 0], 'jasa' => [1, 1, 1, 1], 'kategori' => [1, 0, 0, 0], 'voucher' => [1, 0, 0, 0],
                'request' => [1, 0, 1, 0], 'pesanan' => [1, 0, 1, 0], 'pembayaran' => [1, 0, 1, 0], 'notifikasi' => [1, 0, 1, 1],
                'pengguna' => [0, 0, 0, 0],
            ],
            'client' => [ // Pencari jasa
                'profil' => [1, 0, 1, 0], 'jasa' => [1, 0, 0, 0], 'kategori' => [1, 0, 0, 0], 'voucher' => [1, 0, 0, 0],
                'request' => [1, 1, 1, 1], 'pesanan' => [1, 1, 1, 0], 'pembayaran' => [1, 1, 1, 1], 'notifikasi' => [1, 0, 1, 1],
                'pengguna' => [0, 0, 0, 0],
            ],
        ];

        $actions = array_keys(Permission::ACTIONS);
        foreach ($preset as $role => $modules) {
            $ids = [];
            foreach ($modules as $module => $flags) {
                foreach ($flags as $i => $on) {
                    if ($on) $ids[] = $permissionModels["{$module}.{$actions[$i]}"]->id;
                }
            }
            $roleModels[$role]->permissions()->sync($ids);
        }
    }
}
