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
        $initialMode = $user->isFreelancer() ? 'provider' : 'seeker';

        // Ringkasan per mode (data contoh). Kunci dipakai oleh skena-sheet.js.
        $modes = [
            'seeker' => [
                'l1' => 'Pesanan Aktif',     'n1' => '3',          's1' => 'berjalan',     't1' => '2 sedang dikerjakan penyedia',
                'l2' => 'Total Transaksi',   'n2' => '14',         's2' => '100% tuntas',  't2' => 'Semester ganjil & genap',
                'l3' => 'Total Pengeluaran', 'n3' => 'Rp850.000',  't3' => 'Dari 14 pesanan selesai',
            ],
            'provider' => [
                'l1' => 'Pesanan Masuk',     'n1' => '5',          's1' => 'baru',          't1' => '2 perlu dikonfirmasi hari ini',
                'l2' => 'Jasa Selesai',      'n2' => '28',         's2' => '4.9 rata-rata', 't2' => 'Sejak bergabung di SKENA',
                'l3' => 'Total Penghasilan', 'n3' => 'Rp2.450.000','t3' => 'Dari 28 pesanan selesai',
            ],
        ];
        $summary = $modes[$initialMode];

        $rating = ['value' => '4.9', 'count' => 28];

        // Rekomendasi jasa (data contoh)
        $recommendations = [
            ['img' => 'jasa-ppt.jpg',          'badge' => 'BARU',       'hot' => false, 'cat' => 'Desain PPT',    'rating' => '5.0', 'reviews' => 19, 'title' => 'Desain PPT Sidang & Seminar Skripsi Cepat',      'provider' => 'Nadia S. (DKV ITB)',               'priceLabel' => 'Mulai dari', 'price' => 'Rp20.000', 'unit' => null],
            ['img' => 'jasa-olah-data.jpg',    'badge' => 'POPULER',    'hot' => true,  'cat' => 'Olah Data',     'rating' => '4.9', 'reviews' => 42, 'title' => 'Olah Data Skripsi SPSS & Interpretasi Excel',    'provider' => 'Rian Pratama (Statistika UNPAD)', 'priceLabel' => 'Mulai dari', 'price' => 'Rp50.000', 'unit' => null],
            ['img' => 'jasa-video.jpg',        'badge' => 'BARU',       'hot' => false, 'cat' => 'Video Editing', 'rating' => '4.8', 'reviews' => 11, 'title' => 'Editing Video Reels / TikTok Tugas Kampus',      'provider' => 'Dimas K. (Ilkom UI)',              'priceLabel' => 'Mulai dari', 'price' => 'Rp35.000', 'unit' => null],
            ['img' => 'jasa-tutor-koding.jpg', 'badge' => 'TOP SELLER', 'hot' => true,  'cat' => 'Tutor Koding',  'rating' => '5.0', 'reviews' => 34, 'title' => 'Tutor Privat Koding Python & SQL Tugas Kuliah', 'provider' => 'Fathir A. (Informatika)',          'priceLabel' => 'Biaya sesi', 'price' => 'Rp40.000', 'unit' => '/jam'],
            ['img' => 'jasa-dokumen.jpg',      'badge' => 'BARU',       'hot' => false, 'cat' => 'Dokumen',       'rating' => '4.7', 'reviews' => 8,  'title' => 'Rapikan Format & Daftar Pustaka Skripsi',        'provider' => 'Salsa M. (Sastra UGM)',            'priceLabel' => 'Mulai dari', 'price' => 'Rp25.000', 'unit' => null],
            ['img' => 'jasa-web.jpg',          'badge' => 'BARU',       'hot' => false, 'cat' => 'Web',           'rating' => '4.9', 'reviews' => 15, 'title' => 'Bikin Landing Page Organisasi Kampus',           'provider' => 'Arga P. (Sistem Informasi)',       'priceLabel' => 'Mulai dari', 'price' => 'Rp75.000', 'unit' => null],
        ];

        // Aktivitas pesanan (data contoh). status: work | rev | done | wait
        $orderActivities = [
            ['icon' => 'i-play',  'tone' => '',       'title' => 'Redesign PPT Presentasi Biokimia',      'who' => 'Penyedia', 'name' => 'Nadia S. (DKV ITB)',      'note' => 'Tenggat: Besok, 18.00 WIB',                 'status' => 'work', 'label' => 'Dikerjakan'],
            ['icon' => 'i-chart', 'tone' => 'amber',  'title' => 'Olah Data Regresi Logistik SPSS',       'who' => 'Pencari',  'name' => 'Arif W. (Manajemen)',     'note' => 'Catatan: tambahkan uji multikolinearitas', 'status' => 'rev',  'label' => 'Revisi'],
            ['icon' => 'i-db',    'tone' => 'green',  'title' => 'Setup Database PostgreSQL Tugas Besar', 'who' => 'Penyedia', 'name' => 'Fathir A. (Informatika)', 'note' => 'Selesai 12 Nov 2026',                       'status' => 'done', 'label' => 'Selesai'],
            ['icon' => 'i-pen',   'tone' => 'indigo', 'title' => 'Pembuatan Infografis PKM 2026',         'who' => 'Penyedia', 'name' => 'Bella T. (Arsitektur)',   'note' => 'Menunggu konfirmasi brief',                 'status' => 'wait', 'label' => 'Menunggu'],
        ];

        $categories = ['Semua', 'Desain', 'Tutor', 'Video', 'Web', 'Dokumen'];

        return view('dashboard.index', compact(
            'user', 'initialMode', 'modes', 'summary', 'rating',
            'recommendations', 'orderActivities', 'categories'
        ));
    }
}
