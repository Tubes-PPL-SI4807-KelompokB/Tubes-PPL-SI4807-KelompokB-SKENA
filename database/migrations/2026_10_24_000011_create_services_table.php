<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-04: services (PIC: Anindya Putri Prameswari)
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');
            $table->foreignId('provider_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('service_categories', 'category_id')->restrictOnDelete();
            $table->string('judul', 255);
            $table->text('deskripsi');
            $table->decimal('harga', 12, 2);
            $table->unsignedInteger('durasi')->comment('Estimasi lama pengerjaan dalam hari');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
