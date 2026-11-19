<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();

            // Pesanan yang sedang dinegosiasikan
            $table->foreignId('order_id')
                ->constrained('orders')
                ->restrictOnDelete();

            // Pengguna yang mengajukan penawaran
            $table->foreignId('pengaju_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Harga yang ditawarkan
            $table->decimal('harga_tawar', 12, 2);

            // Status penawaran
            $table->string('status', 30)->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
