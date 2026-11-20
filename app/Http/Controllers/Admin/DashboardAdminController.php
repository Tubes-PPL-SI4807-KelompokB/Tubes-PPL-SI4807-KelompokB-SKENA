<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

/**
 * DashboardAdminController – FR-03
 * Dashboard Administrator & Verifikasi Mahasiswa
 */
class DashboardAdminController extends Controller
{
    public function index()
    {
        // Statistik platform
        $stats = [
            'total_users'       => User::count(),
            'total_freelancers' => User::whereHas('role', fn($q) => $q->where('name', 'freelancer'))->count(),
            'total_clients'     => User::whereHas('role', fn($q) => $q->where('name', 'client'))->count(),
            'verified_students' => User::where('student_verification_status', 'verified')->count(),
            'pending_verifications' => User::where('student_verification_status', 'pending')->count(),
        ];

        // Daftar antrean verifikasi mahasiswa yang masih pending
        $pendingStudents = User::with('role')
            ->where('student_verification_status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        // 5 Pengguna yang baru mendaftar
        $recentUsers = User::with('role')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingStudents', 'recentUsers'));
    }
}
