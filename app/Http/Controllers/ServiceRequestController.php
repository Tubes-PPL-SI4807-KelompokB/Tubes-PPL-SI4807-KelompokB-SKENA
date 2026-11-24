<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;

/**
 * ServiceRequestController – PBI-10 Manajemen Request Jasa (sisi Pencari Jasa)
 * PIC: Mahendra
 *
 * Desain: "PBI-10 Request Jasa" – p10-1 daftar, p10-2 ajukan/ubah, p10-3 batal & kosong.
 * Request yang diterima/ditolak penyedia diproses di PBI-11 (Pesanan).
 */
class ServiceRequestController extends Controller
{
    /** Filter chip: key URL => label */
    public const FILTERS = [
        'semua'                       => 'Semua',
        ServiceRequest::PENDING       => 'Menunggu',
        ServiceRequest::ACCEPTED      => 'Diterima',
        ServiceRequest::REJECTED      => 'Ditolak',
        ServiceRequest::CANCELLED     => 'Dibatalkan',
    ];

    public function __construct(private NotificationService $notifier)
    {
    }

    /** p10-1 – daftar request milik pencari */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $filter = array_key_exists($status, self::FILTERS) ? $status : 'semua';

        $mine = ServiceRequest::where('seeker_id', auth()->id());
        $total = (clone $mine)->count();

        $requests = $mine
            ->with(['service:id,judul,provider_id', 'service.provider:id,name'])
            ->when($filter !== 'semua', fn ($q) => $q->where('status', $filter))
            ->when($search !== '', fn ($q) => $q->where(fn ($sub) => $sub
                ->where('kebutuhan', 'like', "%{$search}%")
                ->orWhereHas('service', fn ($s) => $s->where('judul', 'like', "%{$search}%"))
                ->orWhereHas('service.provider', fn ($p) => $p->where('name', 'like', "%{$search}%"))))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('requests.index', compact('requests', 'search', 'filter', 'total'));
    }

    /** p10-2 – form ajukan request untuk satu jasa */
    public function create(Request $request)
    {
        $serviceId = $request->query('service_id');

        if (!$serviceId) {
            return redirect()->route('home')->with('error', 'Pilih jasa dulu di halaman Jelajahi Jasa.');
        }

        $service = Service::with(['provider:id,name,major,angkatan', 'category:id,nama_kategori'])
            ->active()
            ->find($serviceId);

        abort_if(!$service, 404, 'Jasa tidak ditemukan atau sudah tidak aktif.');

        if ($service->provider_id === auth()->id()) {
            return redirect()->route('home')->with('error', 'Kamu tidak bisa mengajukan request untuk jasamu sendiri.');
        }

        return view('requests.create', compact('service'));
    }

    /** p10-2 – simpan request baru */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            ...$this->rules(),
        ], $this->messages());

        $service = Service::active()->find($validated['service_id']);

        if (!$service) {
            return back()->withInput()
                ->with('error', 'Jasa ini sudah tidak tersedia. Pilih jasa lain di halaman Jelajahi Jasa.');
        }

        if ($service->provider_id === auth()->id()) {
            return back()->withInput()->with('error', 'Kamu tidak bisa mengajukan request untuk jasamu sendiri.');
        }

        $item = ServiceRequest::create([
            'seeker_id'  => auth()->id(),
            'service_id' => $service->id,
            'kebutuhan'  => $validated['kebutuhan'],
            'tanggal'    => $validated['tanggal'],
            'catatan'    => $validated['catatan'] ?? null,
            'status'     => ServiceRequest::PENDING,
        ]);

        $this->notifier->send(
            $service->provider_id,
            'request_baru',
            'Request jasa baru',
            auth()->user()->name.' mengajukan request untuk "'.$service->judul.'".',
            ['service_request_id' => $item->id]
        );

        return redirect()->route('requests.index')->with('success', 'Request terkirim, penyedia akan diberi tahu');
    }

    public function show($id)
    {
        return redirect()->route('requests.index');
    }

    /** p10-2 – ubah request yang masih menunggu */
    public function edit($id)
    {
        $item = $this->pendingOfUser($id);
        $item->load(['service.provider:id,name,major,angkatan', 'service.category:id,nama_kategori']);
        $service = $item->service;

        return view('requests.edit', compact('item', 'service'));
    }

    public function update(Request $request, $id)
    {
        $item = $this->pendingOfUser($id);
        $item->update($request->validate($this->rules(), $this->messages()));

        return redirect()->route('requests.index')->with('success', 'Request berhasil diperbarui');
    }

    /** p10-3 – batalkan request (status diubah, riwayat tetap ada) */
    public function destroy($id)
    {
        $item = $this->pendingOfUser($id);
        $item->update(['status' => ServiceRequest::CANCELLED]);
        $item->loadMissing('service:id,judul,provider_id');

        if ($item->service) {
            $this->notifier->send(
                $item->service->provider_id,
                'request_dibatalkan',
                'Request jasa dibatalkan',
                auth()->user()->name.' membatalkan request untuk "'.$item->service->judul.'".',
                ['service_request_id' => $item->id]
            );
        }

        return redirect()->route('requests.index')->with('success', 'Request dibatalkan, penyedia akan diberi tahu');
    }

    private function pendingOfUser($id): ServiceRequest
    {
        return ServiceRequest::where('seeker_id', auth()->id())
            ->where('status', ServiceRequest::PENDING)
            ->findOrFail($id);
    }

    private function rules(): array
    {
        return [
            'kebutuhan' => ['required', 'string', 'max:5000'],
            'tanggal'   => ['required', 'date', 'after_or_equal:today'],
            'catatan'   => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function messages(): array
    {
        return [
            'service_id.required'     => 'Pilih jasa dulu di halaman Jelajahi Jasa.',
            'service_id.exists'       => 'Jasa ini sudah tidak tersedia. Pilih jasa lain.',
            'kebutuhan.required'      => 'Tuliskan kebutuhanmu agar penyedia paham apa yang diminta.',
            'kebutuhan.max'           => 'Kebutuhan maksimal 5.000 karakter.',
            'tanggal.required'        => 'Pilih tanggal hasil dibutuhkan.',
            'tanggal.date'            => 'Periksa lagi format tanggalnya.',
            'tanggal.after_or_equal'  => 'Pilih tanggal hari ini atau setelahnya.',
            'catatan.max'             => 'Catatan maksimal 2.000 karakter.',
        ];
    }
}
