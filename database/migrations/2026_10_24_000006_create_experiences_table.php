<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-01: experiences
     */
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id('experience_id');
            $table->foreignId('profile_id')->constrained('profiles', 'profile_id')->cascadeOnDelete();
            $table->string('judul');
            $table->string('instansi');
            $table->string('periode', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
