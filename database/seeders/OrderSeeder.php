<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ServiceRequest;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

/**
 * PBI-11 Manajemen Pesanan — pesanan dari request yang sudah diterima (ServiceRequestSeeder).
 * Status mengikuti desain: Dikerjakan, Revisi Klien, Selesai.
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $accepted = ServiceRequest::with('service')
            ->where('status', ServiceRequest::ACCEPTED)
            ->orderBy('created_at')
            ->get();

        if ($accepted->isEmpty()) {
            $this->command?->warn('[OrderSeeder] Belum ada request diterima – jalankan ServiceRequestSeeder dulu.');
            return;
        }

        $voucher = Voucher::where('code', 'MAHASISWA10')->first();
        // Urutan dari request terlama: selesai, revisi, dikerjakan (+voucher), dikerjakan.
        $plan = [Order::SELESAI, Order::REVISI, Order::DIKERJAKAN, Order::DIKERJAKAN];

        foreach ($accepted as $i => $req) {
            $harga  = (int) $req->service->harga;
            $status = $plan[$i] ?? Order::DIKERJAKAN;
            $useVoucher = $voucher && $i === 2;

            $order = Order::updateOrCreate(
                ['request_id' => $req->id],
                [
                    'seeker_id'        => $req->seeker_id,
                    'service_id'       => $req->service_id,
                    'harga_final'      => $harga,
                    'catatan'          => $req->catatan,
                    'voucher_id'       => $useVoucher ? $voucher->id : null,
                    'voucher_discount' => $useVoucher ? $voucher->discountFor($harga) : 0,
                    'status'           => $status,
                ]
            );
            $order->timestamps = false;
            $order->forceFill(['created_at' => $req->updated_at, 'updated_at' => $req->updated_at->copy()->addDays($status === Order::SELESAI ? 3 : 1)])->save();
        }

        $this->command?->info('[OrderSeeder] ' . Order::count() . ' pesanan di-seed.');
    }
}
