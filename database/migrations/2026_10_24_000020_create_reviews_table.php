<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-16: reviews
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('order_id')->unique()->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->comment('Skala 1 - 5');
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
