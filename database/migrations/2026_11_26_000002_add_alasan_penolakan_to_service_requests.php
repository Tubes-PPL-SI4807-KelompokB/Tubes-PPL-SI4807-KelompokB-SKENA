<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PBI-10/PBI-11 — alasan penolakan yang ditulis penyedia di modal "Tolak pesanan"
 * (desain PBI-11 p11-3) dan dikirim ke pencari jasa.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('alasan_penolakan');
        });
    }
};
