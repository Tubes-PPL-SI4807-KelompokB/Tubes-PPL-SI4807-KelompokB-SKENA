<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\RejectServiceRequestRequest;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Voucher;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * PBI-11 Manajemen Pesanan — desain Claude Design "PBI-11 Pesanan":
 *  p11-1 daftar pesanan (Sebagai Penyedia / Sebagai Pencari, cari, filter status),
 *        kartu "Baru" punya tombol Terima / Tolak
 *  p11-2 detail & ubah pesanan (catatan + voucher sebelum pembayaran)
 *  p11-3 modal tolak pesanan dengan alasan + keadaan kosong
 *
 * Kartu "Baru"/"Menunggu"/"Ditolak" berasal dari request jasa (service_requests, PBI-10),
 * sedangkan "Dikerjakan"/"Selesai" berasal dari tabel orders (PBI-11).
 */
class OrderController extends Controller
{
    /** Filter per peran: kunci query => label tombol. */
    public const FILTERS = [
        'penyedia' => ['semua' => 'Semua', 'baru' => 'Baru', 'dikerjakan' => 'Dikerjakan', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'],
        'pencari'  => ['semua' => 'Semua', 'menunggu' => 'Menunggu', 'dikerjakan' => 'Dikerjakan', 'selesai' => 'Selesai'],
    ];

    public function __construct(
        private NotificationService $notifier
    ) {}

    /**
     * p11-1: daftar pesanan milik pengguna, dipisah per peran.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $peran = $request->query('peran');
        if (! array_key_exists($peran, self::FILTERS)) {
            // Bawaan: penyedia jika punya jasa, selain itu pencari.
            $peran = Service::where('provider_id', $user->id)->exists() ? 'penyedia' : 'pencari';
        }

        $status = $request->query('status', 'semua');
        if (! array_key_exists($status, self::FILTERS[$peran])) {
            $status = 'semua';
        }

        $search = trim((string) $request->query('q', ''));

        // Request jasa yang tampil sebagai kartu (Baru/Menunggu/Ditolak).
        $requestStatuses = match ($status) {
            'semua'    => $peran === 'penyedia' ? [ServiceRequest::PENDING, ServiceRequest::REJECTED] : [ServiceRequest::PENDING],
            'baru', 'menunggu' => [ServiceRequest::PENDING],
            'ditolak'  => [ServiceRequest::REJECTED],
            default    => [],
        };

        // Status pesanan yang tampil.
        $orderStatuses = match ($status) {
            'semua'      => [Order::DIKERJAKAN, Order::REVISI, Order::SELESAI, Order::DIBATALKAN],
            'dikerjakan' => [Order::DIKERJAKAN, Order::REVISI],
            'selesai'    => [Order::SELESAI],
            default      => [],
        };

        $ownedBy = function ($q) use ($user, $peran) {
            $peran === 'penyedia'
                ? $q->whereHas('service', fn ($s) => $s->where('provider_id', $user->id))
                : $q->where('seeker_id', $user->id);
        };

        $searchIn = function ($q) use ($search) {
            if ($search === '') {
                return;
            }
            $like = '%' . $search . '%';
            $q->where(function ($w) use ($like) {
                $w->whereHas('service', fn ($s) => $s->where('judul', 'like', $like))
                    ->orWhereHas('seeker', fn ($u) => $u->where('name', 'like', $like))
                    ->orWhereHas('service.provider', fn ($u) => $u->where('name', 'like', $like));
            });
        };

        $orderIds = Order::query()
            ->whereIn('status', $orderStatuses ?: ['-'])
            ->where($ownedBy)->where($searchIn)
            ->toBase()
            ->select(['id', 'created_at', DB::raw("'order' as kind")]);

        $requestIds = ServiceRequest::query()
            ->whereIn('status', $requestStatuses ?: ['-'])
            ->where($ownedBy)->where($searchIn)
            ->toBase()
            ->select(['id', 'created_at', DB::raw("'request' as kind")]);

        $page = DB::query()
            ->fromSub($orderIds->unionAll($requestIds), 'items')
            ->orderByDesc('created_at')->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        // Muat model lengkap dengan eager loading, lalu kembalikan urutannya.
        $ids = collect($page->items())->groupBy('kind')->map(fn ($g) => $g->pluck('id'));
        $with = ['service:id,judul,provider_id,harga', 'service.provider:id,name', 'seeker:id,name'];
        $orders = Order::with([...$with, 'serviceRequest:id,tanggal', 'payment:id,order_id,status'])->whereIn('id', $ids->get('order', []))->get()->keyBy('id');
        $requests = ServiceRequest::with($with)->whereIn('id', $ids->get('request', []))->get()->keyBy('id');

        $page->setCollection(collect($page->items())->map(
            fn ($row) => $row->kind === 'order' ? $orders->get($row->id) : $requests->get($row->id)
        )->filter()->values());

        // Ada data sama sekali untuk peran ini? (membedakan "kosong" dan "tidak cocok").
        $hasAny = Order::where($ownedBy)->exists()
            || ServiceRequest::where($ownedBy)->whereIn('status', [ServiceRequest::PENDING, ServiceRequest::REJECTED])->exists();

        return view('orders.index', [
            'items'   => $page,
            'peran'   => $peran,
            'status'  => $status,
            'search'  => $search,
            'filters' => self::FILTERS[$peran],
            'hasAny'  => $hasAny,
        ]);
    }

    /**
     * Halaman "Request Masuk" lama digabung ke daftar pesanan (filter Baru).
     */
    public function incomingRequests()
    {
        return redirect()->route('orders.index', ['peran' => 'penyedia', 'status' => 'baru']);
    }

    /**
     * p11-2: detail pesanan.
     */
    public function show(Request $request, Order $order)
    {
        $order->load([
            'service:id,judul,provider_id,category_id,harga',
            'service.category:id,nama_kategori',
            'service.provider:id,name,major,angkatan',
            'seeker:id,name,major,angkatan',
            'serviceRequest:id,kebutuhan,tanggal,catatan',
            'voucher:id,code,type,value',
            'payment:id,order_id,status',
        ]);

        abort_unless($order->isVisibleTo($request->user()), 403, 'Kamu tidak memiliki akses ke pesanan ini.');

        return view('orders.show', [
            'order'    => $order,
            'editable' => $order->isEditableBy($request->user()),
        ]);
    }

    /**
     * p11-2: pencari mengubah catatan dan/atau menerapkan voucher sebelum pembayaran.
     * Tombol "Terapkan" hanya menyimpan lalu kembali; "Simpan Perubahan" lanjut ke pembayaran.
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->load(['service:id,provider_id,harga', 'payment:id,order_id,status', 'voucher']);
        abort_unless($order->isEditableBy($request->user()), 403, 'Pesanan ini tidak bisa diubah lagi.');

        $data = $request->validated();
        $code = strtoupper(trim((string) ($data['kode_voucher'] ?? '')));
        $currentCode = $order->voucher?->code;

        $result = DB::transaction(function () use ($order, $data, $code, $currentCode) {
            $order->catatan = $data['catatan'] ?? null;

            if ($code !== ($currentCode ?? '')) {
                $new = null;
                if ($code !== '') {
                    $new = Voucher::where('code', $code)->lockForUpdate()->first();
                    if (! $new || ! $new->isUsable() || ! $new->redeem()) {
                        return self::voucherProblem($new, $code);
                    }
                }

                // Kembalikan kuota voucher lama.
                if ($order->voucher_id) {
                    Voucher::whereKey($order->voucher_id)->where('used_count', '>', 0)->decrement('used_count');
                }

                $order->voucher_id = $new?->id;
                $order->voucher_discount = $new ? $new->discountFor($order->harga_jasa) : 0;
            }

            $order->save();

            return 'ok';
        });

        if ($result !== 'ok') {
            return back()->withInput()->withErrors(['kode_voucher' => $result]);
        }

        if ($request->input('aksi') === 'terapkan') {
            $msg = $order->voucher_id
                ? 'Voucher ' . $code . ' diterapkan, hemat Rp' . number_format($order->voucher_discount, 0, ',', '.') . '.'
                : 'Voucher dilepas dari pesanan.';

            return redirect()->route('orders.show', $order)->with('voucher_msg', $msg);
        }

        return redirect()
            ->route('payments.create', ['order_id' => $order->id])
            ->with('success', 'Perubahan pesanan ' . $order->code . ' disimpan. Lanjutkan pembayaran.');
    }

    /** Pesan spesifik kenapa voucher tidak bisa dipakai (desain p11-2: pesan error + langkah perbaikan). */
    private static function voucherProblem(?Voucher $voucher, string $code): string
    {
        if (! $voucher) {
            return 'Kode voucher ' . $code . ' tidak ditemukan. Periksa lagi penulisannya.';
        }

        return match ($voucher->status) {
            'Kedaluwarsa' => 'Voucher ' . $code . ' sudah kedaluwarsa sejak ' . $voucher->end_date->translatedFormat('j M Y') . '. Coba kode lain.',
            'Terjadwal'   => 'Voucher ' . $code . ' baru bisa dipakai mulai ' . $voucher->start_date->translatedFormat('j M Y') . '.',
            'Nonaktif'    => 'Voucher ' . $code . ' sedang tidak aktif. Coba kode lain.',
            default       => 'Kuota voucher ' . $code . ' sudah habis. Coba kode lain.',
        };
    }

    /**
     * p11-1: penyedia menerima request → pesanan dibuat dengan status Dikerjakan.
     */
    public function acceptRequest($id)
    {
        $result = DB::transaction(function () use ($id) {
            $serviceRequest = ServiceRequest::with('service')->lockForUpdate()->findOrFail($id);

            abort_unless(
                (int) $serviceRequest->service->provider_id === (int) auth()->id(),
                403,
                'Kamu tidak memiliki akses untuk menerima request ini.'
            );

            if (! $serviceRequest->isPending() || Order::where('request_id', $serviceRequest->id)->exists()) {
                return null;
            }

            $serviceRequest->update(['status' => ServiceRequest::ACCEPTED]);

            $order = Order::create([
                'request_id'  => $serviceRequest->id,
                'seeker_id'   => $serviceRequest->seeker_id,
                'service_id'  => $serviceRequest->service_id,
                'harga_final' => $serviceRequest->service->harga,
                'catatan'     => $serviceRequest->catatan,
                'status'      => Order::DIKERJAKAN,
            ]);

            return [$order, $serviceRequest];
        });

        if (! $result) {
            return back()->with('error', 'Request ini sudah diproses sebelumnya. Muat ulang halaman untuk melihat status terbaru.');
        }

        [$order, $serviceRequest] = $result;

        $this->notifier->send(
            $serviceRequest->seeker_id,
            'request_diterima',
            'Request jasa diterima',
            'Request untuk "' . $serviceRequest->service->judul . '" diterima menjadi pesanan ' . $order->code . '. Lakukan pembayaran agar pesanan diproses.',
            ['order_id' => $order->id]
        );

        return back()->with('success', 'Pesanan ' . $order->code . ' diterima dan mulai dikerjakan.');
    }

    /**
     * p11-3: penyedia menolak request dengan alasan yang dikirim ke pencari jasa.
     */
    public function rejectRequest(RejectServiceRequestRequest $request, $id)
    {
        $alasan = $request->validated()['alasan_penolakan'];

        $serviceRequest = DB::transaction(function () use ($id, $alasan) {
            $serviceRequest = ServiceRequest::with('service')->lockForUpdate()->findOrFail($id);

            abort_unless(
                (int) $serviceRequest->service->provider_id === (int) auth()->id(),
                403,
                'Kamu tidak memiliki akses untuk menolak request ini.'
            );

            if (! $serviceRequest->isPending() || Order::where('request_id', $serviceRequest->id)->exists()) {
                return null;
            }

            $serviceRequest->update([
                'status'           => ServiceRequest::REJECTED,
                'alasan_penolakan' => $alasan,
            ]);

            return $serviceRequest;
        });

        if (! $serviceRequest) {
            return back()->with('error', 'Request ini sudah diproses sebelumnya. Muat ulang halaman untuk melihat status terbaru.');
        }

        $this->notifier->send(
            $serviceRequest->seeker_id,
            'request_ditolak',
            'Request jasa ditolak',
            'Request untuk "' . $serviceRequest->service->judul . '" ditolak. Alasan: ' . $alasan,
            ['request_id' => $serviceRequest->id]
        );

        return back()->with('success', 'Pesanan "' . $serviceRequest->service->judul . '" ditolak dan alasannya dikirim ke pencari jasa.');
    }
}
