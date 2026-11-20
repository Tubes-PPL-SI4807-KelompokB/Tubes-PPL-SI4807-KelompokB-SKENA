<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->where('seeker_id', auth()->user()->getAuthIdentifier())
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

    /**
     * Menampilkan form pengajuan request.
     */
    public function create(Request $request)
    {
        $serviceId = $request->query('service_id');

        if (!$serviceId) {
            return redirect()
                ->route('home')
                ->with('error', 'Silakan pilih jasa terlebih dahulu.');
        }

        // Mengambil informasi jasa dan penyedianya.
        // Mengikuti rancangan migration terbaru.
        $service = DB::table('services')
            ->leftJoin('users as providers', 'providers.user_id', '=', 'services.provider_id')
            ->where('services.service_id', $serviceId)
            ->where('services.status', 'aktif')
            ->select(
                'services.service_id',
                'services.judul',
                'services.deskripsi',
                'services.harga',
                'services.durasi',
                'providers.nama as provider_name'
            )
            ->first();

        if (!$service) {
            abort(404, 'Jasa tidak ditemukan atau sudah tidak aktif.');
        }

        return view('requests.create', compact('service'));
    }

    /**
     * Menyimpan request baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,service_id'],
            'kebutuhan' => ['required', 'string', 'max:5000'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'service_id.required' => 'Jasa harus dipilih.',
            'kebutuhan.required' => 'Kebutuhan jasa wajib diisi.',
            'tanggal.required' => 'Tanggal dibutuhkan wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
        ]);

        // Pastikan jasa masih aktif sebelum request disimpan.
        $serviceIsActive = DB::table('services')
            ->where('service_id', $validated['service_id'])
            ->where('status', 'aktif')
            ->exists();

        if (!$serviceIsActive) {
            return back()
                ->withInput()
                ->with('error', 'Jasa sudah tidak tersedia.');
        }

        ServiceRequest::create([
            'seeker_id' => auth()->user()->getAuthIdentifier(),
            'service_id' => $validated['service_id'],
            'kebutuhan' => $validated['kebutuhan'],
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('requests.index')
            ->with('success', 'Request jasa berhasil dikirim!');
    }

    public function show($id)
    {
        // Akan dilengkapi pada tahap detail request.
    }

    public function edit($id)
    {
        $item = ServiceRequest::with('service')
            ->where('seeker_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        return view('requests.edit', compact('item'));
    }

    /**
     * Memperbarui request.
     */
    public function update(Request $request, $id)
    {
        $item = ServiceRequest::where('seeker_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'kebutuhan' => ['required', 'string', 'max:5000'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $item->update($validated);

        return redirect()
            ->route('requests.index')
            ->with('success', 'Request jasa berhasil diperbarui!');
    }

    /**
     * Membatalkan request.
     */
    public function destroy($id)
    {
        $item = ServiceRequest::where('seeker_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        // Status diubah, bukan menghapus riwayat request.
        $item->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('requests.index')
            ->with('success', 'Request jasa berhasil dibatalkan!');
    }
}
