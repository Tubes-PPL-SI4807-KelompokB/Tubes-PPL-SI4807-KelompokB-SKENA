<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-06: portfolios (PIC: Anindya Putri Prameswari)
     */
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id('portfolio_id');
            $table->foreignId('provider_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services', 'service_id')->nullOnDelete();
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->string('berkas_tautan', 500)->nullable();
            $table->boolean('status_tampil')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
