<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()->paginate(10);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        $this->authoriseNotification($request, $notification);

        $notification->markAsRead();

        return $notification->link
            ? redirect($notification->link)
            : back();
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        $user->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('status', 'All notifications marked as read.');
    }

    private function authoriseNotification(Request $request, Notification $notification): void
    {
        if ($notification->user_id !== $request->user()->id) 
        {
            abort(403);
        }
    }
}
