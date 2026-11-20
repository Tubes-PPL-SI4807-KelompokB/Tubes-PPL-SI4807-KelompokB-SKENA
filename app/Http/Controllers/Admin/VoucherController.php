<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;

/**
 * VoucherController – PBI-09 (CRUD Manajemen Voucher/Promo, khusus admin)
 * PIC: Rizal
 *
 * Pemakaian kode saat memesan ada di modul Pesanan (PBI-11) lewat
 * Voucher::findUsable(), discountFor(), dan redeem().
 */
class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $status = in_array($request->query('status'), Voucher::STATUSES, true) ? $request->query('status') : null;

        $vouchers = Voucher::query()
            ->search($request->query('q'))
            ->status($status)
            ->latest('start_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.vouchers.index', [
            'vouchers' => $vouchers,
            'status'   => $status ?? 'Semua',
            'q'        => (string) $request->query('q', ''),
            'hasAny'   => Voucher::exists(),
        ]);
    }

    public function create()
    {
        return view('admin.vouchers.form', ['voucher' => new Voucher([
            'type'       => Voucher::TYPE_PERCENT,
            'value'      => 10,
            'start_date' => today(),
            'end_date'   => today()->addMonth(),
            'quota'      => 100,
            'used_count' => 0,
            'is_active'  => true,
        ])]);
    }

    public function store(VoucherRequest $request)
    {
        $voucher = Voucher::create($request->validated());

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher {$voucher->code} berhasil ditambahkan.");
    }

    public function show($id)
    {
        return redirect()->route('admin.vouchers.edit', $id);
    }

    public function edit($id)
    {
        return view('admin.vouchers.form', ['voucher' => Voucher::findOrFail($id)]);
    }

    public function update(VoucherRequest $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->validated());

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher {$voucher->code} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher {$voucher->code} dihapus.");
    }
}
