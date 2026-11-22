<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user   = auth()->user();
        $filter = $request->input('filter', 'semua');

        $query = Payment::with(['order.service', 'payee', 'payer'])->latest();

        // Admin dapat melihat semua transaksi; user reguler hanya melihat transaksi terkait dirinya
        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('payer_id', $user->id)
                  ->orWhere('payee_id', $user->id);
            });
        }

        if ($filter === 'menunggu') {
            $query->whereIn('status', [
                Payment::STATUS_MENUNGGU_PEMBAYARAN,
                Payment::STATUS_MENUNGGU_KONFIRMASI,
            ]);
        } elseif ($filter === 'lunas') {
            $query->where('status', Payment::STATUS_LUNAS);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($q2) use ($search) {
                      $q2->where('id', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(10)->withQueryString();

        return view('payments.index', compact('payments', 'filter'));
    }

    public function create(Request $request)
    {
        $user    = auth()->user();
        $orderId = (int) $request->input('order_id', 1);
        $order   = Order::with('service')->find($orderId);

        if (!$order) {
            $order = new Order();
            $order->id = $orderId;
        }

        // Penyedia jasa tidak dapat membayar layanannya sendiri
        if ($order->service && (int)$order->service->user_id === (int)$user->id && !$user->isAdmin()) {
            abort(403, 'Sebagai penyedia jasa, Anda tidak dapat melakukan pembayaran untuk layanan Anda sendiri.');
        }

        // Hanya pencari jasa pembeli pesanan atau admin yang berhak unggah bukti
        if ($order->buyer_id && !$user->isAdmin() && (int)$order->buyer_id !== (int)$user->id) {
            abort(403, 'Hanya pencari jasa yang bersangkutan yang dapat mengunggah bukti pembayaran pesanan ini.');
        }

        if ($order->payment && $order->payment->status === Payment::STATUS_LUNAS) {
            return redirect()->route('payments.index')
                ->with('success', 'Pembayaran pesanan ini sudah lunas.');
        }

        $payment = $order->payment;

        return view('payments.create', compact('order', 'payment'));
    }

    public function store(PaymentRequest $request)
    {
        $user    = auth()->user();
        $orderId = (int) $request->validated()['order_id'];
        $order   = Order::with('service')->firstOrCreate(['id' => $orderId]);

        // Penyedia jasa tidak dapat membayar layanannya sendiri
        if ($order->service && (int)$order->service->user_id === (int)$user->id && !$user->isAdmin()) {
            abort(403, 'Sebagai penyedia jasa, Anda tidak dapat melakukan pembayaran untuk layanan Anda sendiri.');
        }

        // Hanya pencari jasa atau admin
        if ($order->buyer_id && !$user->isAdmin() && (int)$order->buyer_id !== (int)$user->id) {
            abort(403, 'Hanya pencari jasa yang bersangkutan yang dapat mengunggah bukti pembayaran pesanan ini.');
        }

        if ($order->payment && $order->payment->status === Payment::STATUS_LUNAS) {
            return back()->with('error', 'Pembayaran pesanan ini sudah lunas.');
        }

        $file   = $request->file('proof');
        $path   = $file->store('payments', 'public');
        $amount = $order->final_price ?? $order->agreed_price ?? ($order->service->price ?? 18000);

        // Tentukan penerima pembayaran (payee)
        $payeeId = $order->service->user_id ?? ($order->payment->payee_id ?? null);
        if (!$payeeId || (int)$payeeId === (int)$user->id) {
            $otherUser = User::where('id', '!=', $user->id)->first();
            $payeeId   = $otherUser?->id ?? $user->id;
        }

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'payer_id'            => $user->id,
                'payee_id'            => $payeeId,
                'amount'              => $amount,
                'status'              => Payment::STATUS_MENUNGGU_KONFIRMASI,
                'proof_path'          => $path,
                'proof_original_name' => $file->getClientOriginalName(),
                'proof_size'          => $file->getSize(),
                'proof_uploaded_at'   => now(),
                'rejection_note'      => null,
                'confirmed_at'        => null,
            ]
        );

        return redirect()->route('payments.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi penyedia jasa.');
    }

    public function show($id)
    {
        $payment = Payment::with(['order.service', 'payer', 'payee'])->find($id);

        if (!$payment) {
            return redirect()->route('payments.index')
                ->with('error', "Pembayaran #{$id} belum ada di database.");
        }

        $user = auth()->user();

        // Hanya pihak terkait (pembeli, penjual) atau admin yang boleh melihat detail pembayaran
        $isRelated = $user->isAdmin() ||
                     (int)$payment->payer_id === (int)$user->id ||
                     (int)$payment->payee_id === (int)$user->id;

        abort_if(!$isRelated, 403, 'Anda tidak memiliki hak akses untuk melihat pembayaran ini.');

        return view('payments.show', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        $user    = auth()->user();

        // Hanya penyedia jasa penerima pembayaran (payee) atau admin yang berhak konfirmasi / tolak
        $canConfirm = $user->isAdmin() || (int)$payment->payee_id === (int)$user->id;
        abort_if(!$canConfirm, 403, 'Hanya penyedia jasa penerima pembayaran atau administrator yang berhak mengonfirmasi atau menolak pembayaran.');

        abort_if($payment->status !== Payment::STATUS_MENUNGGU_KONFIRMASI, 422, 'Status pembayaran tidak dapat diubah.');

        $action = $request->input('action');

        if ($action === 'confirm') {
            $payment->update([
                'status'       => Payment::STATUS_LUNAS,
                'confirmed_at' => now(),
            ]);

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Pembayaran berhasil dikonfirmasi. Pesanan dinyatakan lunas.');
        }

        if ($action === 'reject') {
            $request->validate([
                'rejection_note' => ['nullable', 'string', 'max:500'],
            ], [
                'rejection_note.max' => 'Catatan penolakan maksimal 500 karakter.',
            ]);

            $payment->update([
                'status'         => Payment::STATUS_DITOLAK,
                'rejection_note' => $request->input('rejection_note'),
            ]);

            return redirect()->route('payments.show', $payment)
                ->with('error', 'Bukti pembayaran ditolak. Pencari jasa dapat mengunggah ulang.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $user    = auth()->user();

        // Hanya pencari jasa (payer) atau admin yang dapat menghapus bukti selama belum diproses
        $canDelete = $user->isAdmin() || (int)$payment->payer_id === (int)$user->id;
        abort_if(!$canDelete, 403, 'Hanya pencari jasa pengunggah bukti atau administrator yang berhak menghapus bukti.');
        abort_if(!$payment->isEditable(), 422, 'Bukti tidak dapat dihapus karena pembayaran sudah diproses.');

        if ($payment->proof_path) {
            Storage::disk('public')->delete($payment->proof_path);
        }

        $payment->update([
            'status'              => Payment::STATUS_MENUNGGU_PEMBAYARAN,
            'proof_path'          => null,
            'proof_original_name' => null,
            'proof_size'          => null,
            'proof_uploaded_at'   => null,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Bukti pembayaran berhasil dihapus. Silakan unggah bukti baru.');
    }
}
