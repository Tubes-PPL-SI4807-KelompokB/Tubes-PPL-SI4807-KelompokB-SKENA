<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed bawaan: akun, role, kategori, skill, jasa, dan voucher.
     * Daftar request jasa, pesanan, dan pembayaran sengaja dibiarkan kosong agar
     * alur pemesanan bisa dicoba dari awal. Data contoh transaksi tetap tersedia:
     *   php artisan db:seed --class=ServiceRequestSeeder   (PBI-10)
     *   php artisan db:seed --class=OrderSeeder            (PBI-11)
     *   php artisan db:seed --class=PaymentSeeder          (PBI-13)
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ServiceCategorySeeder::class,
            SkillSeeder::class,
            ServiceSeeder::class,
            VoucherSeeder::class,
        ]);
    }
}
