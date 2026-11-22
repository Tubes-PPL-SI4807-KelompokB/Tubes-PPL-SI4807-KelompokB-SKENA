<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $payer = User::where('email', 'anindya@student.telkomuniversity.ac.id')->first()
               ?? User::skip(2)->first()
               ?? User::first();

        $payee = User::where('email', 'earliyanza@student.telkomuniversity.ac.id')->first()
               ?? User::skip(1)->first()
               ?? User::first();

        if (!$payer || !$payee) {
            $this->command->warn('[PaymentSeeder] Tidak ada user tersedia – skip.');
            return;
        }

        if (Order::count() === 0) {
            for ($k = 1; $k <= 5; $k++) {
                \Illuminate\Support\Facades\DB::table('orders')->insertOrIgnore([
                    'id'         => $k,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $orders = Order::take(5)->get();

        $rows = [
            [18000, Payment::STATUS_MENUNGGU_KONFIRMASI, 3,  null],
            [35000, Payment::STATUS_LUNAS,               5,  4],
            [30000, Payment::STATUS_LUNAS,               10, 9],
            [30000, Payment::STATUS_LUNAS,               16, 15],
            [45000, Payment::STATUS_MENUNGGU_KONFIRMASI,  3,  null],
        ];

        foreach ($rows as $i => [$amount, $status, $uploadDaysAgo, $confirmDaysAgo]) {
            $order = $orders->get($i);
            if (!$order) continue;

            $uploadedAt  = Carbon::now()->subDays($uploadDaysAgo);
            $confirmedAt = $confirmDaysAgo !== null
                ? Carbon::now()->subDays($confirmDaysAgo)
                : null;

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payer_id'            => $payer->id,
                    'payee_id'            => $payee->id,
                    'amount'              => $amount,
                    'status'              => $status,
                    'proof_path'          => 'payments/sample-bukti-' . ($i + 1) . '.jpg',
                    'proof_original_name' => 'bukti-transfer-SKN-00' . (640 + $i + 2) . '.jpg',
                    'proof_size'          => rand(200000, 800000),
                    'proof_uploaded_at'   => $uploadedAt,
                    'confirmed_at'        => $confirmedAt,
                    'rejection_note'      => null,
                ]
            );
        }

        $this->command->info('[PaymentSeeder] ' . min(count($rows), $orders->count()) . ' data pembayaran berhasil di-seed.');
    }
}
