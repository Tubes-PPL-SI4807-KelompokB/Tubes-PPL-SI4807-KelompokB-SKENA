<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-02: skills
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id('skill_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('nama_skill', 100);
            $table->string('kategori', 50)->nullable();
            $table->enum('level', ['Pemula', 'Menengah', 'Mahir'])->default('Menengah');
            $table->string('lama_pengalaman', 50)->nullable();
            $table->string('bukti')->nullable();
            $table->boolean('unggulan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
