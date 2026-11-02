<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * DashboardAdminController – FR-03
 * PIC: Tim
 */
class DashboardAdminController extends Controller
{
    public function index()
    {
        // TODO: FR-03 – tampilkan statistik platform (user, order, transaksi)
        return view('admin.dashboard');
    }
}
