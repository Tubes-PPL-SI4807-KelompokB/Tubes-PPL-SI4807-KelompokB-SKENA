<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    /**
     * Menampilkan daftar request milik pengguna.
     */

public function index(Request $request)
{
    $search = trim((string) $request->query('q', ''));

    $allowedStatuses = [
        'pending',
        'accepted',
        'rejected',
        'cancelled',
    ];

    $status = $request->query('status');

    if (!in_array($status, $allowedStatuses, true)) {
        $status = null;
    }

    $requests = ServiceRequest::with('service')
        ->where('seeker_id', auth()->id())
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('kebutuhan', 'like', "%{$search}%")
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('judul', 'like', "%{$search}%");
                    });
            });
        })
        ->latest()
        ->paginate(9)
        ->withQueryString();

    return view('requests.index', compact('requests', 'search', 'status'));
}


    public function create()
    {
        // Akan dilengkapi pada tahap form pengajuan.
    }

    public function store(Request $request)
    {
        // Akan dilengkapi pada tahap penyimpanan request.
    }

    public function show($id)
    {
        // Akan dilengkapi jika diperlukan.
    }

    public function edit($id)
    {
        // Akan dilengkapi pada tahap edit request.
    }

    public function update(Request $request, $id)
    {
        // Akan dilengkapi pada tahap update request.
    }

    public function destroy($id)
    {
        // Akan dilengkapi pada tahap pembatalan request.
    }
}
