<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();

            // Pengguna yang mengajukan request jasa
            $table->foreignId('seeker_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Jasa yang diminta
            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            // Informasi permintaan jasa
            $table->text('kebutuhan');
            $table->date('tanggal');
            $table->text('catatan')->nullable();

            // Status permintaan jasa
            $table->string('status', 30)->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
