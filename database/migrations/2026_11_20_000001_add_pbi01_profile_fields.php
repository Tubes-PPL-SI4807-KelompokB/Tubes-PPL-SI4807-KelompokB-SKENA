<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PBI-01 – kolom untuk desain "Kelola Profil":
 *  - experiences: riwayat pengalaman & organisasi (tabel sebelumnya masih kosong)
 *  - profiles.is_available: toggle "Terima Pesanan"
 *  - users.angkatan: tahun angkatan mahasiswa
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['organisasi', 'kerja', 'kepanitiaan'])->default('organisasi')->after('user_id');
            $table->string('name')->after('type');                  // Nama pengalaman/organisasi
            $table->string('role')->after('name');                  // Peran
            $table->string('start_period', 20)->after('role');      // mis. "Sep 2023"
            $table->string('end_period', 20)->nullable()->after('start_period'); // kosong = sekarang
            $table->text('description')->nullable()->after('end_period');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('hourly_rate');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('angkatan')->nullable()->after('major');
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('angkatan'));
        Schema::table('profiles', fn (Blueprint $table) => $table->dropColumn('is_available'));
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['type', 'name', 'role', 'start_period', 'end_period', 'description']);
        });
    }
};
