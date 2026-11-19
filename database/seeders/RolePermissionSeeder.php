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

        // 2. Buat Permissions
        $permissions = [
            ['name' => 'manage_users', 'display_name' => 'Kelola Pengguna', 'description' => 'Melihat, mengubah status, atau menghapus pengguna'],
            ['name' => 'manage_roles', 'display_name' => 'Kelola Hak Akses', 'description' => 'Mengatur role dan permission pengguna'],
            ['name' => 'verify_students', 'display_name' => 'Verifikasi Mahasiswa', 'description' => 'Memeriksa dan menyetujui KTM mahasiswa'],
            ['name' => 'view_admin_logs', 'display_name' => 'Lihat Log Admin', 'description' => 'Melihat jejak aktivitas audit sistem'],
            ['name' => 'moderate_content', 'display_name' => 'Moderasi Konten', 'description' => 'Meninjau laporan konten atau jasa'],
            ['name' => 'publish_service', 'display_name' => 'Publikasi Jasa', 'description' => 'Membuat dan menerbitkan penawaran jasa kreatif'],
            ['name' => 'manage_portfolio', 'display_name' => 'Kelola Portofolio', 'description' => 'Mengunggah hasil karya dan proyek'],
            ['name' => 'order_service', 'display_name' => 'Pesan Jasa', 'description' => 'Melakukan checkout dan order jasa kreatif'],
            ['name' => 'make_payment', 'display_name' => 'Melakukan Pembayaran', 'description' => 'Membayar pesanan melalui payment gateway/escrow'],
            ['name' => 'write_review', 'display_name' => 'Beri Ulasan', 'description' => 'Memberi rating dan ulasan pesanan yang selesai'],
        ];

        $permissionModels = [];
        foreach ($permissions as $p) {
            $permissionModels[$p['name']] = Permission::updateOrCreate(['name' => $p['name']], $p);
        }

        // 3. Kaitkan Permission ke Role
        // Admin: semua permission
        $roleModels['admin']->permissions()->sync(collect($permissionModels)->pluck('id'));

        // Freelancer: publish_service, manage_portfolio, write_review
        $freelancerPerms = collect([
            $permissionModels['publish_service']->id,
            $permissionModels['manage_portfolio']->id,
            $permissionModels['write_review']->id,
        ]);
        $roleModels['freelancer']->permissions()->sync($freelancerPerms);

        // Client: order_service, make_payment, write_review
        $clientPerms = collect([
            $permissionModels['order_service']->id,
            $permissionModels['make_payment']->id,
            $permissionModels['write_review']->id,
        ]);
        $roleModels['client']->permissions()->sync($clientPerms);
    }
}
