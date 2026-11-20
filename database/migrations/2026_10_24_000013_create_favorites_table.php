<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-07: favorites (Wishlist Jasa)
     */
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id('favorite_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services', 'service_id')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
