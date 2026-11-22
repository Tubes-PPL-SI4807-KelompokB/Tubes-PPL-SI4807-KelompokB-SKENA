<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('payer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('payee_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_konfirmasi',
                'lunas',
                'ditolak',
            ])->default('menunggu_pembayaran');
            $table->string('proof_path')->nullable();
            $table->string('proof_original_name')->nullable();
            $table->unsignedInteger('proof_size')->nullable();
            $table->timestamp('proof_uploaded_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->text('rejection_note')->nullable();
            $table->timestamps();

            $table->unique('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
