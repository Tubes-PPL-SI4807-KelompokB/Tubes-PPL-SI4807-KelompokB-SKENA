<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceFormRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

/**
 * ServiceController – PBI-04 Manajemen Jasa (FR-10)
 * PIC: Anindya Putri Prameswari
 *
 * Penyedia mengelola jasanya sendiri: daftar + filter status (p04-1),
 * tambah/ubah dengan pratinjau kartu (p04-2), hapus dengan konfirmasi (p04-3),
 * dan aktif/nonaktif langsung dari kartu. Detail jasa publik ada di
 * ServiceDetailController (FR-26).
 */
class ServiceController extends Controller
{
    public const FILTERS = ['semua' => 'Semua', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'];

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $filter = array_key_exists($request->input('status'), self::FILTERS) ? $request->input('status') : 'semua';
        $search = trim((string) $request->input('q'));

        $services = Service::with('category')
            ->where('provider_id', $userId)
            ->when($filter !== 'semua', fn ($q) => $q->where('status', $filter))
            ->when($search !== '', fn ($q) => $q->where(function ($w) use ($search) {
                $w->where('judul', 'like', "%{$search}%")->orWhere('deskripsi', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $counts = Service::where('provider_id', $userId)
            ->selectRaw("count(*) as total, sum(case when status = 'aktif' then 1 else 0 end) as aktif")
            ->first();
        $total  = (int) $counts->total;
        $active = (int) $counts->aktif;

        return view('services.index', compact('services', 'filter', 'search', 'total', 'active'));
    }

    public function create()
    {
        return view('services.create', [
            'service'    => new Service(['status' => 'aktif', 'durasi' => '2 hari']),
            'categories' => ServiceCategory::orderBy('nama_kategori')->get(),
            'durations'  => ServiceFormRequest::DURATIONS,
        ]);
    }

    public function store(ServiceFormRequest $request)
    {
        $service = Service::create($request->validated() + ['provider_id' => $request->user()->id]);

        return redirect()->route('services.index')
            ->with('success', "Jasa \"{$service->judul}\" berhasil disimpan.");
    }

    public function edit(Service $service)
    {
        $this->authorizeOwner($service);

        return view('services.edit', [
            'service'    => $service,
            'categories' => ServiceCategory::orderBy('nama_kategori')->get(),
            'durations'  => ServiceFormRequest::DURATIONS,
        ]);
    }

    public function update(ServiceFormRequest $request, Service $service)
    {
        $this->authorizeOwner($service);
        $service->update($request->validated());

        return redirect()->route('services.index')
            ->with('success', "Perubahan jasa \"{$service->judul}\" berhasil disimpan.");
    }

    /** Switch aktif/nonaktif di kartu daftar (operasi aktifkan() pada Class Diagram). */
    public function toggleStatus(Request $request, Service $service)
    {
        $this->authorizeOwner($service);
        $service->toggleStatus();

        return back()->with('success', $service->status === 'aktif'
            ? "Jasa \"{$service->judul}\" aktif dan tampil di pencarian."
            : "Jasa \"{$service->judul}\" dinonaktifkan. Hanya kamu yang bisa melihatnya.");
    }

    public function destroy(Service $service)
    {
        $this->authorizeOwner($service);

        // Jasa yang sudah punya request/pesanan tetap disimpan agar riwayat transaksi utuh.
        if ($service->hasTransactions()) {
            $service->update(['status' => 'nonaktif']);

            return back()->with('error', "Jasa \"{$service->judul}\" sudah punya request atau pesanan, jadi tidak dihapus. Jasa ini kami nonaktifkan agar tidak tampil di pencarian.");
        }

        $judul = $service->judul;
        $service->delete();

        return redirect()->route('services.index')->with('success', "Jasa \"{$judul}\" berhasil dihapus.");
    }

    private function authorizeOwner(Service $service): void
    {
        abort_unless($service->provider_id === auth()->id(), 403, 'Kamu hanya bisa mengelola jasa milikmu sendiri.');
    }
}
