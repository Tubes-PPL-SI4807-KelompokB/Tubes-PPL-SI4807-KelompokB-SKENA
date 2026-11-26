<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ServiceCategorySeeder::class,
            SkillSeeder::class,
            ServiceSeeder::class,
            VoucherSeeder::class,
            ServiceRequestSeeder::class, // PBI-10
            OrderSeeder::class,          // PBI-11
            PaymentSeeder::class,
        ]);
    }
}
