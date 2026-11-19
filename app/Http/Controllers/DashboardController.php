<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController – FR-04
 * Dashboard Pengguna (Freelancer Mahasiswa & Klien)
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

        // Summary statistics dummy/live data
        $stats = [
            'active_orders'     => 2,
            'completed_orders'  => 5,
            'total_earnings'    => $user->isFreelancer() ? 2750000 : 0,
            'total_spent'       => $user->isClient() ? 1850000 : 0,
            'published_services'=> $user->isFreelancer() ? 3 : 0,
            'saved_freelancers' => $user->isClient() ? 4 : 0,
        ];

        // Aktivitas terbaru
        $recentActivities = [
            [
                'title' => 'Verifikasi Mahasiswa',
                'description' => $user->isVerifiedStudent() 
                    ? 'Identitas mahasiswa Anda telah diverifikasi oleh Tim SKENA.' 
                    : ($user->student_verification_status === 'pending' 
                        ? 'Pengajuan KTM sedang dalam proses peninjauan tim verifikator.' 
                        : 'Lengkapi data kemahasiswaan Anda untuk mendapatkan badge terverifikasi.'),
                'time' => 'Baru saja',
                'type' => $user->isVerifiedStudent() ? 'success' : 'info',
            ],
            [
                'title' => 'Pembaruan Profil',
                'description' => 'Lengkapi portofolio dan keahlian Anda agar profil lebih mudah ditemukan oleh klien.',
                'time' => '1 hari yang lalu',
                'type' => 'info',
            ],
        ];

        return view('dashboard.index', compact('user', 'stats', 'recentActivities'));
    }
}
