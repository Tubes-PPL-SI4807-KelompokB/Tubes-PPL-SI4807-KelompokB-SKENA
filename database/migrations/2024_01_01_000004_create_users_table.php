<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            
            // Status akun
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            
            // Verifikasi Mahasiswa (Fondasi verifikasi identitas mahasiswa kreatif)
            $table->enum('student_verification_status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified');
            $table->string('nim')->nullable();
            $table->string('university')->nullable();
            $table->string('faculty')->nullable();
            $table->string('major')->nullable();
            $table->string('ktm_path')->nullable();               // Path file scan/foto KTM
            $table->text('verification_notes')->nullable();       // Catatan admin saat verifikasi/penolakan
            $table->timestamp('verified_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
