<?php

namespace App\Http\View\Composers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with([
                'unreadNotifCount' => 0,
                'latestNotifs' => collect(),
            ]);
            return;
        }

        $userId = Auth::id();

        $view->with([
            'unreadNotifCount' => Notifikasi::where('user_id', $userId)
                ->where('is_read', false)
                ->count(),
            'latestNotifs' => Notifikasi::where('user_id', $userId)
                ->where('is_read', false)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
