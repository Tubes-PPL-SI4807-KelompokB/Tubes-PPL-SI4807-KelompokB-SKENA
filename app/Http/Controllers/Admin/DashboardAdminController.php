<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * DashboardAdminController – FR-03 (TIM-D)
 * Ringkasan platform: 5 metrik + aktivitas terbaru (pola Dashboard, hanya-baca).
 *
 * Tabel services, orders, payments, dan reports masih kerangka (PBI-04/11/13/18),
 * jadi metriknya dihitung apa adanya (0) dengan catatan "Belum ada data".
 */
class DashboardAdminController extends Controller
{
    public function index()
    {
        $count = fn (string $table) => DB::table($table)->count();
        $since = fn (string $table, $from) => DB::table($table)->where('created_at', '>=', $from)->count();

        $providers = User::whereHas('role', fn ($q) => $q->where('name', 'freelancer'))->count();
        $seekers   = User::whereHas('role', fn ($q) => $q->where('name', 'client'))->count();

        $services = $count('services');
        $orders   = $count('orders');
        $payments = $count('payments');
        $reports  = $count('reports');

        $stats = [
            ['label' => 'TOTAL PENGGUNA', 'icon' => 'group', 'value' => User::count(),
             'note' => "{$providers} Penyedia / {$seekers} Pencari"],
            ['label' => 'JASA AKTIF', 'icon' => 'storefront', 'value' => $services,
             'note' => $services ? $since('services', now()->startOfWeek()) . ' jasa baru minggu ini' : 'Belum ada data'],
            ['label' => 'PESANAN', 'icon' => 'receipt_long', 'value' => $orders,
             'note' => $orders ? $since('orders', now()->startOfDay()) . ' pesanan hari ini' : 'Belum ada data'],
            ['label' => 'TRANSAKSI', 'icon' => 'payments', 'value' => $payments,
             'note' => $payments ? $since('payments', now()->startOfMonth()) . ' transaksi bulan ini' : 'Belum ada data'],
            ['label' => 'LAPORAN MENUNGGU TINDAK LANJUT', 'icon' => 'flag', 'value' => $reports,
             'note' => $reports ? $since('reports', now()->startOfDay()) . ' masuk hari ini' : 'Belum ada data'],
        ];

        // Aktivitas terbaru: pendaftaran pengguna & pengajuan KTM, terbaru di atas.
        $activities = User::with('role')->latest()->take(6)->get()->map(fn (User $u) => [
            'icon'  => 'person_add',
            'title' => 'Pengguna baru: ' . $u->name,
            'meta'  => collect([
                $u->major ?: ($u->role?->display_name ?? 'Pengguna'),
                in_array($u->student_verification_status, ['pending', 'unverified']) && $u->isFreelancer() ? 'menunggu verifikasi' : $u->email,
            ])->implode(' · '),
            'time'  => $u->created_at->locale('id')->diffForHumans(),
            'url'   => route('admin.users.show', $u),
        ]);

        return view('admin.dashboard', compact('stats', 'activities'));
    }
}
