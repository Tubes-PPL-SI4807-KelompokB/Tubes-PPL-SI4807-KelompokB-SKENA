<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('service_categories')->restrictOnDelete();
            $table->string('judul', 255);
            $table->text('deskripsi');
            $table->decimal('harga', 12, 2);
            $table->unsignedInteger('durasi')->comment('Estimasi lama pengerjaan dalam hari');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
