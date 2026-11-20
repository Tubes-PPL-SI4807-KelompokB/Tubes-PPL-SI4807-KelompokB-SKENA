<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-01: profiles
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('kampus')->default('Telkom University');
            $table->string('prodi')->nullable();
            $table->string('angkatan', 10)->nullable();
            $table->string('kontak', 50)->nullable();
            $table->string('foto')->nullable();
            $table->boolean('status_tersedia')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
