<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Fetch Unread Notifications
    public function fetch()
    {
        $notifications = Auth::user()->notifications()->whereNull('read_at')->latest()->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    // Mark Notification as Read
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    // Unread Count for Navbar
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }
}
