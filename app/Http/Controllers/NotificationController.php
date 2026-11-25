<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Notifikasi telah ditandai dibaca.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->notifications()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function unreadCount(): JsonResponse
    {
        $count = Auth::user()->notifications()->where('is_read', false)->count();

        return response()->json(['unread_count' => $count]);
    }
}
