<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-09: vouchers
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->string('kode', 50)->unique();
            $table->enum('jenis', ['persen', 'nominal'])->default('nominal');
            $table->decimal('nilai_potongan', 12, 2);
            $table->date('berlaku_mulai');
            $table->date('berlaku_sampai');
            $table->unsignedInteger('kuota')->default(0);
            $table->enum('status', ['aktif', 'nonaktif', 'habis'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
