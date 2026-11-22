<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('skill_id')
                ->constrained('skills')
                ->cascadeOnDelete();

            $table->string('proficiency', 30)->default('pemula');
            $table->decimal('experience_years', 4, 1)->default(0);
            $table->string('proof_path')->nullable();
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            $table->unique(['user_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};