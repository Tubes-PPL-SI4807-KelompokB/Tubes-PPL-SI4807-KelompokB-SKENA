<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Request jasa yang telah diterima
            // Satu request hanya dapat menjadi satu pesanan
            $table->foreignId('request_id')
                ->unique()
                ->constrained('service_requests')
                ->restrictOnDelete();

            // Pengguna yang melakukan pemesanan
            $table->foreignId('seeker_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Jasa yang dipesan
            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            // Voucher bersifat opsional
            $table->foreignId('voucher_id')
                ->nullable()
                ->constrained('vouchers')
                ->nullOnDelete();

            // Harga akhir setelah negosiasi disepakati
            $table->decimal('harga_final', 12, 2)->nullable();

            // Status pesanan
            $table->string('status', 30)->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
