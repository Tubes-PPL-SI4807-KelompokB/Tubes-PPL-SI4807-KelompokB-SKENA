<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-12: negotiations
     */
    public function up(): void
    {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id('negotiation_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('pengaju_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->decimal('harga_tawar', 12, 2);
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
