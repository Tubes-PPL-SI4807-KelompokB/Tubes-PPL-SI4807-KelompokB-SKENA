<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * PBI-17 – daftar notifikasi
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * PBI-17 – tandai notifikasi sudah dibaca
     */
    public function update(Request $request, $id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        // Tombol "Lihat": tandai dibaca lalu buka halaman terkait.
        if ($request->boolean('buka') && $notification->url) {
            return redirect($notification->url);
        }

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notifikasi berhasil ditandai sudah dibaca.');
    }
}
