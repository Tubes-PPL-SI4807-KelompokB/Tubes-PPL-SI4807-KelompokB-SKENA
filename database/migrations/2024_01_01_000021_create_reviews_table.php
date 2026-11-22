
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('reviewed_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('status', 30)->default('published');

            $table->timestamps();

            $table->unique(['order_id', 'reviewer_id']);
            $table->index(['reviewed_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
