<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-10: service_requests
     */
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->foreignId('seeker_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services', 'service_id')->cascadeOnDelete();
            $table->text('kebutuhan');
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->enum('status', ['Menunggu', 'Diterima', 'Ditolak', 'Dibatalkan'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
