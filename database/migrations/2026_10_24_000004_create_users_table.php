<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fondasi: users (FR-01, FR-02, FR-05)
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->foreignId('role_id')->nullable()->constrained('roles', 'role_id')->nullOnDelete();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('nim', 30)->unique()->nullable();
            $table->string('password');
            $table->enum('status_verifikasi', ['terverifikasi', 'menunggu', 'ditolak'])->default('menunggu');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
