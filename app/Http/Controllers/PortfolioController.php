<?php

namespace App\Http\Controllers;

use App\Http\Requests\PortfolioRequest;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * PBI-06: CRUD Manajemen Portfolio (Sprint 2)
 * PIC: Anindya Putri Prameswari (102022400026)
 * Aktor: Penyedia Jasa (FR-12)
 * Sesuai Mockup Gambar 4.5.7: Kelola Portfolio
 */
class PortfolioController extends Controller
{
    /**
     * Dapatkan ID pengguna penyedia jasa saat ini.
     * Fallback ke ID user pertama jika auth belum login (untuk kemudahan testing mandiri).
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
     * Menampilkan halaman Kelola Portfolio.
     * Memuat daftar kartu portofolio dengan filter jasa, serta form input karya baru.
     */
    public function index(Request $request)
    {
        $providerId = $this->getCurrentProviderId();
        $serviceId = $request->input('service_id');

        $query = Portfolio::with('service')
            ->where('provider_id', $providerId);

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }

        $portfolios = $query->latest()->get();

        // Daftar jasa milik penyedia untuk opsi dropdown filter & tautan karya
        $services = Service::where('provider_id', $providerId)
            ->orderBy('judul')
            ->get();

        $totalCount = $portfolios->count();

        return view('portfolios.index', compact('portfolios', 'services', 'serviceId', 'totalCount'));
    }

    /**
     * Menampilkan form terpisah untuk tambah portofolio.
     */
    public function create()
    {
        $providerId = $this->getCurrentProviderId();
        $services = Service::where('provider_id', $providerId)->orderBy('judul')->get();

        return view('portfolios.create', compact('services'));
    }

    /**
     * Menyimpan karya portofolio baru.
     */
    public function store(PortfolioRequest $request)
    {
        $data = $request->validated();
        $data['provider_id'] = $this->getCurrentProviderId();
        $data['status_tampil'] = $request->has('status_tampil');

        // Penanganan upload berkas atau tautan eksternal
        if ($request->hasFile('berkas')) {
            $path = $request->file('berkas')->store('portfolios', 'public');
            $data['berkas_tautan'] = $path;
        } elseif ($request->filled('tautan_eksternal')) {
            $data['berkas_tautan'] = $request->input('tautan_eksternal');
        }

        unset($data['berkas'], $data['tautan_eksternal']);

        $portfolio = Portfolio::create($data);

        return redirect()
            ->route('portfolios.index')
            ->with('success', "Karya '{$portfolio->judul}' berhasil ditambahkan ke portofolio.");
    }

    /**
     * Menampilkan form edit karya portofolio.
     */
    public function edit(Portfolio $portfolio)
    {
        $providerId = $this->getCurrentProviderId();
        $services = Service::where('provider_id', $providerId)->orderBy('judul')->get();

        return view('portfolios.edit', compact('portfolio', 'services'));
    }

    /**
     * Memperbarui karya portofolio yang ada.
     */
    public function update(PortfolioRequest $request, Portfolio $portfolio)
    {
        $data = $request->validated();
        $data['status_tampil'] = $request->has('status_tampil');

        if ($request->hasFile('berkas')) {
            // Hapus file lama jika tersimpan di disk lokal
            if ($portfolio->berkas_tautan && !filter_var($portfolio->berkas_tautan, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($portfolio->berkas_tautan);
            }

            $path = $request->file('berkas')->store('portfolios', 'public');
            $data['berkas_tautan'] = $path;
        } elseif ($request->filled('tautan_eksternal')) {
            $data['berkas_tautan'] = $request->input('tautan_eksternal');
        }

        unset($data['berkas'], $data['tautan_eksternal']);

        $portfolio->update($data);

        return redirect()
            ->route('portfolios.index')
            ->with('success', "Karya '{$portfolio->judul}' berhasil diperbarui.");
    }

    /**
     * Menghapus karya portofolio.
     */
    public function destroy(Portfolio $portfolio)
    {
        $judul = $portfolio->judul;

        // Hapus file dari storage jika ada
        if ($portfolio->berkas_tautan && !filter_var($portfolio->berkas_tautan, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($portfolio->berkas_tautan);
        }

        $portfolio->delete();

        return redirect()
            ->route('portfolios.index')
            ->with('success', "Karya '{$judul}' berhasil dihapus dari portofolio.");
    }

    /**
     * Toggle status tampil portofolio.
     */
    public function toggleVisibility(Portfolio $portfolio)
    {
        $portfolio->toggleVisibility();

        $statusMsg = $portfolio->status_tampil ? 'sekarang ditampilkan' : 'sekarang disembunyikan';

        return redirect()
            ->back()
            ->with('success', "Karya '{$portfolio->judul}' {$statusMsg}.");
    }
}
