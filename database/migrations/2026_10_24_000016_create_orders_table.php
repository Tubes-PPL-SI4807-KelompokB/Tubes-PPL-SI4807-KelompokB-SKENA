<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-11: orders
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('request_id')->unique()->constrained('service_requests', 'request_id')->cascadeOnDelete();
            $table->foreignId('seeker_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services', 'service_id')->cascadeOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers', 'voucher_id')->nullOnDelete();
            $table->decimal('harga_final', 12, 2)->nullable();
            $table->enum('status', ['Menunggu', 'Negosiasi', 'Dikerjakan', 'Revisi', 'Selesai', 'Dibatalkan'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
