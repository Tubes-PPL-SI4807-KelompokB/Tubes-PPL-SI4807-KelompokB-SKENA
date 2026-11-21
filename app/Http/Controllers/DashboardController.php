<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        // Rekomendasi jasa (data contoh desain)
        $services = [
            ['POPULER', 'Desain', '4.9', 'Desain PPT Presentasi Sidang dan Seminar', 'Nadia Salsabila · DKV 2022', 'Rp20.000', 'jasa-ppt.jpg'],
            ['BARU', 'Tutor', '4.8', 'Tutor SQL dan Basis Data untuk Pemula', 'Dimas Kurniawan · Informatika 2021', 'Rp30.000/jam', 'jasa-tutor-koding.jpg'],
            ['POPULER', 'Video', '4.9', 'Edit Video Reels dan Dokumentasi Acara', 'Rara Anindya · Ilmu Komunikasi 2022', 'Rp35.000', 'jasa-video.jpg'],
            ['BARU', 'Web', '4.7', 'Setup Website Portofolio Pribadi', 'Bagas Pratama · Sistem Informasi 2021', 'Rp50.000', 'jasa-web.jpg'],
            ['POPULER', 'Data', '4.9', 'Olah Data Skripsi SPSS dan Interpretasi Hasil', 'Rian Pratama · Statistika 2021', 'Rp50.000', 'jasa-olah-data.jpg'],
            ['BARU', 'Dokumen', '4.7', 'Rapikan Format dan Daftar Pustaka Skripsi', 'Salsa Maharani · Sastra Indonesia 2022', 'Rp25.000', 'jasa-dokumen.jpg'],
            ['POPULER', 'Desain', '4.8', 'Desain Poster dan Feed Instagram Acara Kampus', 'Bella Tanjung · DKV 2023', 'Rp30.000', 'jasa-ppt.jpg'],
            ['BARU', 'Tutor', '4.9', 'Tutor Python Dasar untuk Tugas Algoritma', 'Fathir Rahman · Sistem Informasi 2023', 'Rp40.000/jam', 'jasa-tutor-koding.jpg'],
            ['POPULER', 'Video', '4.8', 'Edit Video Dokumentasi KKN dan Organisasi', 'Dimas Kurniawan · Ilmu Komunikasi 2021', 'Rp45.000', 'jasa-video.jpg'],
            ['BARU', 'Web', '4.6', 'Bikin Landing Page Organisasi Kampus', 'Arga Prasetyo · Informatika 2022', 'Rp75.000', 'jasa-web.jpg'],
            ['POPULER', 'Data', '4.8', 'Dashboard Excel untuk Laporan Keuangan UKM', 'Nabila Putri · Akuntansi 2022', 'Rp35.000', 'jasa-olah-data.jpg'],
            ['BARU', 'Dokumen', '4.7', 'Proofreading Abstrak dan Jurnal Bahasa Inggris', 'Kevin Wijaya · Sastra Inggris 2021', 'Rp30.000', 'jasa-dokumen.jpg'],
        ];

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
