<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url', 2048)->nullable();
            $table->enum('status', ['tampil', 'disembunyikan'])
                ->default('tampil');

            $table->timestamps();

            $table->index(['service_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};