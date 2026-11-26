<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ServiceRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private NotificationService $notifier
    ) {}

    /**
     * PBI-11: Menampilkan daftar pesanan milik pengguna.
     * Pencari dan penyedia dapat melihat pesanan yang terkait dengannya.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Order::with([
            'service:id,judul,provider_id,harga',
            'service.provider:id,name',
            'buyer:id,name',
            'serviceRequest:id,kebutuhan,tanggal,status',
        ]);

        // Pengguna biasa hanya melihat pesanan yang terkait dengannya.
        // Admin dapat melihat seluruh pesanan.
        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('seeker_id', $user->id)
                    ->orWhereHas('service', function ($serviceQuery) use ($user) {
                        $serviceQuery->where('provider_id', $user->id);
                    });
            });
        }

        // Filter status pesanan.
        $status = $request->query('status');

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        // Pencarian berdasarkan nama jasa atau kebutuhan.
        $search = trim((string) $request->query('q', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('judul', 'like', "%{$search}%");
                })->orWhereHas('serviceRequest', function ($requestQuery) use ($search) {
                    $requestQuery->where('kebutuhan', 'like', "%{$search}%");
                });
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('orders.index', compact('orders', 'status', 'search'));
    }

    /**
     * PBI-11: Menampilkan request masuk untuk penyedia jasa.
     */
    public function incomingRequests(Request $request)
    {
        $query = ServiceRequest::with([
            'seeker:id,name',
            'service:id,judul,provider_id,harga',
        ])
            ->where('status', ServiceRequest::PENDING)
            ->whereHas('service', function ($serviceQuery) {
                $serviceQuery->where('provider_id', auth()->id());
            })
            ->latest();

        $search = trim((string) $request->query('q', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kebutuhan', 'like', "%{$search}%")
                    ->orWhereHas('seeker', function ($seekerQuery) use ($search) {
                        $seekerQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('orders.incoming_request', compact('requests', 'search'));
    }

    /**
     * PBI-11: Menampilkan detail pesanan.
     */
    public function show($id)
    {
        $order = Order::with([
            'service.provider:id,name',
            'buyer:id,name',
            'serviceRequest',
            'negotiations.pengaju:id,name',
            'payment',
        ])->findOrFail($id);

        $user = auth()->user();

        $isRelated = $user->isAdmin()
            || (int) $order->seeker_id === (int) $user->id
            || (int) $order->service->provider_id === (int) $user->id;

        abort_unless($isRelated, 403, 'Kamu tidak memiliki akses ke pesanan ini.');

        return view('orders.show', compact('order'));
    }

    /**
     * PBI-11: Penyedia menerima request dan membuat pesanan.
     */
    public function acceptRequest($id)
    {
        $result = DB::transaction(function () use ($id) {
            $serviceRequest = ServiceRequest::with('service')
                ->lockForUpdate()
                ->findOrFail($id);

            // Hanya penyedia jasa terkait yang boleh menerima request.
            abort_unless(
                (int) $serviceRequest->service->provider_id === (int) auth()->id(),
                403,
                'Kamu tidak memiliki akses untuk menerima request ini.'
            );

            // Request harus masih menunggu dan belum pernah dibuat pesanan.
            if (
                !$serviceRequest->isPending()
                || Order::where('request_id', $serviceRequest->id)->exists()
            ) {
                return null;
            }

            $serviceRequest->update([
                'status' => ServiceRequest::ACCEPTED,
            ]);

            $order = Order::create([
                'request_id' => $serviceRequest->id,
                'seeker_id' => $serviceRequest->seeker_id,
                'service_id' => $serviceRequest->service_id,
                'harga_final' => null,
                'status' => 'pending',
            ]);

            return [
                'order' => $order,
                'seeker_id' => $serviceRequest->seeker_id,
                'service_title' => $serviceRequest->service->judul,
            ];
        });

        if (!$result) {
            return redirect()
                ->route('orders.incoming')
                ->with('error', 'Request ini sudah diproses atau sudah menjadi pesanan.');
        }

        // Beri tahu pencari jasa bahwa request diterima.
        $this->notifier->send(
            $result['seeker_id'],
            'request_diterima',
            'Request jasa diterima',
            'Request untuk "' . $result['service_title'] . '" telah diterima. Pesanan berhasil dibuat.',
            ['order_id' => $result['order']->id]
        );

        return redirect()
            ->route('orders.incoming')
            ->with('success', 'Request berhasil diterima dan pesanan berhasil dibuat.');
    }

    /**
     * PBI-11: Penyedia menolak request.
     */
    public function rejectRequest($id)
    {
        $result = DB::transaction(function () use ($id) {
            $serviceRequest = ServiceRequest::with('service')
                ->lockForUpdate()
                ->findOrFail($id);

            // Hanya penyedia jasa terkait yang boleh menolak request.
            abort_unless(
                (int) $serviceRequest->service->provider_id === (int) auth()->id(),
                403,
                'Kamu tidak memiliki akses untuk menolak request ini.'
            );

            if (
                !$serviceRequest->isPending()
                || Order::where('request_id', $serviceRequest->id)->exists()
            ) {
                return null;
            }

            $serviceRequest->update([
                'status' => ServiceRequest::REJECTED,
            ]);

            return [
                'seeker_id' => $serviceRequest->seeker_id,
                'service_title' => $serviceRequest->service->judul,
            ];
        });

        if (!$result) {
            return redirect()
                ->route('orders.incoming')
                ->with('error', 'Request ini sudah diproses.');
        }

        // Beri tahu pencari jasa bahwa request ditolak.
        $this->notifier->send(
            $result['seeker_id'],
            'request_ditolak',
            'Request jasa ditolak',
            'Maaf, request untuk "' . $result['service_title'] . '" ditolak oleh penyedia jasa.'
        );

        return redirect()
            ->route('orders.incoming')
            ->with('success', 'Request berhasil ditolak.');
    }
}
