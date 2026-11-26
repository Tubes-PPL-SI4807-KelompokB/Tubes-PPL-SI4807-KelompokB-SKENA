<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * PBI-11 Manajemen Pesanan — sesuaikan tabel orders dengan desain "PBI-11 Pesanan":
 * - catatan pesanan yang bisa diubah pencari sebelum pembayaran (p11-2)
 * - potongan voucher yang tersimpan saat voucher diterapkan (p11-2, dibaca PBI-13)
 * - status memakai nilai Bahasa Indonesia: dikerjakan / revisi / selesai / dibatalkan
 * - harga_final diisi harga jasa saat request diterima (negosiasi PBI-12 dapat mengubahnya)
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('harga_final');
            $table->unsignedInteger('voucher_discount')->default(0)->after('voucher_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 30)->default('dikerjakan')->change();
            $table->index('status');
        });

        $map = [
            'pending'     => 'dikerjakan',
            'in_progress' => 'dikerjakan',
            'completed'   => 'selesai',
            'cancelled'   => 'dibatalkan',
        ];
        foreach ($map as $old => $new) {
            DB::table('orders')->where('status', $old)->update(['status' => $new]);
        }

        // Pesanan lama yang belum punya harga: ambil harga jasa saat ini.
        DB::table('orders')->whereNull('harga_final')->orderBy('id')->each(function ($order) {
            $harga = DB::table('services')->where('id', $order->service_id)->value('harga');
            DB::table('orders')->where('id', $order->id)->update(['harga_final' => $harga]);
        });
    }

    public function down(): void
    {
        $map = ['dikerjakan' => 'pending', 'revisi' => 'in_progress', 'selesai' => 'completed', 'dibatalkan' => 'cancelled'];
        foreach ($map as $old => $new) {
            DB::table('orders')->where('status', $old)->update(['status' => $new]);
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->string('status', 30)->default('pending')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['catatan', 'voucher_discount']);
        });
    }
};
