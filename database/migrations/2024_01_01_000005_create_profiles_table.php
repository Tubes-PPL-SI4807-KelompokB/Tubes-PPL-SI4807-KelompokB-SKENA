<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('headline')->nullable();              // e.g. "Creative Logo & UI/UX Designer"
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->string('city')->nullable();
            $table->string('website')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->decimal('hourly_rate', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
