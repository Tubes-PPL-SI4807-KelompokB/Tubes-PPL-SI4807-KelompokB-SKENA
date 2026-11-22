
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('moderasi_konten', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')
                ->nullable()
                ->constrained('reports')
                ->nullOnDelete();

            $table->foreignId('moderator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('content_type', 100);
            $table->unsignedBigInteger('content_id');

            $table->string('action', 50);
            $table->text('reason')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamp('moderated_at')->nullable();

            $table->timestamps();

            $table->index(['content_type', 'content_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderasi_konten');
    }
};
