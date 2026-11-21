<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * PBI-04: CRUD Manajemen Jasa (Sprint 1)
 * PIC: Anindya Putri Prameswari (102022400026)
 * Aktor: Penyedia Jasa (FR-10)
 */
class ServiceController extends Controller
{
    /**
     * Dapatkan ID pengguna penyedia jasa saat ini.
     * Fallback ke ID user pertama jika auth belum login (untuk kemudahan testing/seeding).
     */
    protected function getCurrentProviderId(): int
    {
        if (Auth::check()) {
            return (int) Auth::id();
        }

        $fallbackUser = User::first();
        return $fallbackUser ? (int) $fallbackUser->id : 1;
    }

    /**
     * Menampilkan daftar jasa milik penyedia.
     */
    public function index(Request $request)
    {
        $providerId = $this->getCurrentProviderId();
        $status = $request->input('status');
        $categoryId = $request->input('category_id');
        $search = $request->input('search');

        $query = Service::with(['category', 'portfolios'])
            ->where('provider_id', $providerId);

        if ($status && in_array($status, ['aktif', 'nonaktif'])) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $services = $query->latest()->paginate(9)->withQueryString();
        $categories = ServiceCategory::orderBy('nama_kategori')->get();

        // Statistik singkat
        $totalServices = Service::where('provider_id', $providerId)->count();
        $activeServices = Service::where('provider_id', $providerId)->where('status', 'aktif')->count();
        $inactiveServices = Service::where('provider_id', $providerId)->where('status', 'nonaktif')->count();

        return view('services.index', compact(
            'services',
            'categories',
            'status',
            'categoryId',
            'search',
            'totalServices',
            'activeServices',
            'inactiveServices'
        ));
    }

    /**
     * Menampilkan form untuk membuat jasa baru.
     */
    public function create()
    {
        $categories = ServiceCategory::orderBy('nama_kategori')->get();
        return view('services.create', compact('categories'));
    }

    /**
     * Menyimpan data jasa baru.
     */
    public function store(ServiceRequest $request)
    {
        $data = $request->validated();
        $data['provider_id'] = $this->getCurrentProviderId();
        $data['status'] = $request->input('status', 'aktif');

        $service = Service::create($data);

        return redirect()
            ->route('services.index')
            ->with('success', "Jasa '{$service->judul}' berhasil dibuat dan diterbitkan.");
    }

    /**
     * Menampilkan detail jasa beserta portofolio terkait.
     */
    public function show(Service $service)
    {
        $service->load(['category', 'provider', 'portfolios' => function ($q) {
            $q->where('status_tampil', true)->latest();
        }]);

        return view('services.show', compact('service'));
    }

    /**
     * Menampilkan form edit jasa.
     */
    public function edit(Service $service)
    {
        $categories = ServiceCategory::orderBy('nama_kategori')->get();
        return view('services.edit', compact('service', 'categories'));
    }

    /**
     * Memperbarui data jasa yang ada.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $data = $request->validated();
        $data['status'] = $request->input('status', $service->status);

        $service->update($data);

        return redirect()
            ->route('services.index')
            ->with('success', "Jasa '{$service->judul}' berhasil diperbarui.");
    }

    /**
     * Menghapus jasa.
     */
    public function destroy(Service $service)
    {
        $judul = $service->judul;
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', "Jasa '{$judul}' berhasil dihapus.");
    }

    /**
     * Mengaktifkan atau menonaktifkan status jasa.
     * Sesuai operasi 'aktifkan()' pada Class Diagram SKENA (FR-10).
     */
    public function toggleStatus(Service $service)
    {
        $service->toggleStatus();

        $statusText = $service->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->back()
            ->with('success', "Status jasa '{$service->judul}' berhasil {$statusText}.");
    }
}
