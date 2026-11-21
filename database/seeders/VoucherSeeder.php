<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['MAHASISWA10', 'percent', 10,    '2026-11-01', '2026-12-31', 200, 124, true],
            ['HEMAT5K',     'nominal', 5000,  '2026-10-15', '2026-11-30', 100, 88,  true],
            ['WISUDA20',    'percent', 20,    '2026-12-01', '2026-12-31', 150, 0,   true],
            ['UTSAMAN',     'nominal', 3000,  '2026-10-01', '2026-10-31', 300, 300, true],
            ['MABA2026',    'percent', 15,    '2026-08-01', '2026-09-30', 250, 212, true],
            ['TESTPROMO',   'nominal', 10000, '2026-11-01', '2026-11-30', 20,  2,   false],
        ];

        foreach ($rows as [$code, $type, $value, $start, $end, $quota, $used, $active]) {
            Voucher::updateOrCreate(['code' => $code], [
                'type' => $type, 'value' => $value, 'start_date' => $start, 'end_date' => $end,
                'quota' => $quota, 'used_count' => $used, 'is_active' => $active,
            ]);
        }
    }
}
