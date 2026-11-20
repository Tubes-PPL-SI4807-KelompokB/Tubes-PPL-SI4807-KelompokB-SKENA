<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-03: moderasi_konten
     */
    public function up(): void
    {
        Schema::create('moderasi_konten', function (Blueprint $table) {
            $table->id('moderasi_id');
            $table->foreignId('admin_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('objek_tipe', 50)->comment('jasa, portfolio, ulasan');
            $table->unsignedBigInteger('objek_id');
            $table->enum('status_moderasi', ['tampil', 'disembunyikan', 'dihapus'])->default('tampil');
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderasi_konten');
    }
};
