
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('content_type', 100);
            $table->unsignedBigInteger('content_id');

            $table->string('reason', 150);
            $table->text('description')->nullable();
            $table->string('status', 30)->default('pending');

            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('handled_at')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->timestamps();

            $table->index(['content_type', 'content_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
