<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-14: work_progress
     */
    public function up(): void
    {
        Schema::create('work_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->enum('status', ['Menunggu', 'Dikerjakan', 'Revisi', 'Selesai'])->default('Menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_progress');
    }
};
