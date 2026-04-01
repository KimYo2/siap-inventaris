<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Show full notification list and mark all as read.
     */
    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        // Mark all unread as read on page visit
        Notifikasi::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifikasi.index', compact('notifikasi'));
    }

    /**
     * Mark a single notification as read and redirect back.
     */
    public function markRead(Request $request, $id)
    {
        Notifikasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);

        return redirect()->back();
    }
}
