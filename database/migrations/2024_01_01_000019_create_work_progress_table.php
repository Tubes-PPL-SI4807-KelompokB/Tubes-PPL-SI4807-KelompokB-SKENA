
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('updated_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('attachment_path')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_progress');
    }
};
