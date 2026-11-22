<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * DashboardController – FR-04
 * Dashboard Pengguna (Pencari Jasa & Penyedia Jasa)
 *
 * CATATAN: ringkasan, rekomendasi jasa, dan aktivitas pesanan masih DATA CONTOH.
 * Tabel services (PBI-04) dan orders (PBI-11) belum terisi; ganti dengan query
 * setelah modul tersebut selesai. Dashboard transaksi/penghasilan = PBI Tim Sprint 2.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika admin membuka route /dashboard, arahkan ke dashboard admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Mode awal: penyedia untuk akun freelancer, pencari untuk lainnya.
        $initialMode = $user->isFreelancer() ? 'penyedia' : 'pencari';

        // Ringkasan per mode — data contoh sesuai Claude Design "Dashboard Page".
        $modes = [
            'pencari' => [
                ['PESANAN AKTIF', 'receipt_long', '3', '2 menunggu konfirmasi penyedia'],
                ['TOTAL TRANSAKSI', 'swap_horiz', '14', 'Sejak Agu 2026'],
                ['TOTAL PENGELUARAN', 'account_balance_wallet', 'Rp485.000', 'Bulan ini Rp120.000'],
                ['RATING RATA-RATA', 'star', '4.9', 'Dari 11 ulasan penyedia'],
            ],
            'penyedia' => [
                ['PESANAN AKTIF', 'receipt_long', '5', '1 pesanan baru hari ini'],
                ['TOTAL TRANSAKSI', 'swap_horiz', '27', 'Sejak Agu 2026'],
                ['TOTAL PENDAPATAN', 'account_balance_wallet', 'Rp1.240.000', 'Bulan ini Rp310.000'],
                ['RATING RATA-RATA', 'star', '4.8', 'Dari 22 ulasan pencari'],
            ],
        ];

        // Rekomendasi jasa: 8 jasa aktif terbaru dari tabel services (PBI-04)
        $images = ['desain' => 'jasa-ppt.jpg', 'tutor' => 'jasa-tutor-koding.jpg', 'video' => 'jasa-video.jpg',
                   'web' => 'jasa-web.jpg', 'data' => 'jasa-olah-data.jpg', 'office' => 'jasa-olah-data.jpg', 'foto' => 'jasa-ppt.jpg'];
        $services = Service::active()
            ->with(['provider:id,name', 'category:id,nama_kategori'])
            ->when($user, fn ($q) => $q->where('provider_id', '!=', $user->id))
            ->latest()
            ->take(8)
            ->get()
            ->map(function (Service $s) use ($images) {
                $catName = $s->category?->nama_kategori ?? 'Lainnya';
                $img = collect($images)->first(fn ($file, $key) => Str::contains(Str::lower($catName), $key), 'jasa-ppt.jpg');

                return [
                    $s->created_at?->gt(now()->subDays(14)) ? 'BARU' : 'JASA',
                    Str::before($catName, ' &'),
                    null, // rating diisi setelah PBI-16 Review tersedia
                    $s->judul,
                    $s->provider?->name ?? 'Penyedia SKENA',
                    $s->formatted_harga,
                    $img,
                    route('requests.create', ['service_id' => $s->id]),
                ];
            })
            ->all();

        // Aktivitas pesanan (data contoh desain)
        $orders = [
            ['design_services', 'Desain PPT Sidang Skripsi', 'Nadia S. · Tenggat Besok, 18.00 WIB · Rp20.000', 'Dikerjakan'],
            ['movie', 'Edit Video Profil UKM Fotografi', 'Rara A. · Tenggat 20 Nov 2026 · Rp35.000', 'Revisi Klien'],
            ['school', 'Tutor SQL Pertemuan 2', 'Dimas K. · 12 Nov 2026 · Rp30.000', 'Selesai'],
            ['description', 'Rapikan Format Laporan KP', 'Salma R. · Diajukan 18 Nov 2026 · Rp25.000', 'Menunggu'],
        ];

        $features = [
            ['sell', 'Harga Terjangkau', 'Sesuai kantong mahasiswa'],
            ['receipt', 'Pembayaran Tercatat', 'Setiap transaksi terekam'],
            ['handshake', 'Negosiasi Harga', 'Sepakati harga bersama'],
            ['star', 'Ulasan & Rating', 'Pilih dari yang terpercaya'],
        ];

        $categories = ['Semua', 'Desain', 'Tutor', 'Video', 'Web', 'Dokumen'];

        return view('dashboard.index', compact('user', 'initialMode', 'modes', 'services', 'orders', 'features', 'categories'));
    }
}
