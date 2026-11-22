<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('provider_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->constrained('service_categories')
                ->restrictOnDelete();

            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->decimal('harga', 12, 2);
            $table->string('durasi', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();

            $table->index(['provider_id', 'status']);
            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};