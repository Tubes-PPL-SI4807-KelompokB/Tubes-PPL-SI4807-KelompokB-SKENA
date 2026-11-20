<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-18: reports (Laporan Pengaduan)
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->foreignId('reporter_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('objek_tipe', 50)->comment('jasa, user, ulasan');
            $table->unsignedBigInteger('objek_id');
            $table->text('alasan');
            $table->string('bukti')->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'dibatalkan'])->default('baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
