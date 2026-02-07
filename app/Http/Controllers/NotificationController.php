<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query();
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        if ($request->filled('read')) {
            $query->where('is_read', $request->read === 'true');
        }
        $notifications = $query->latest()->paginate(20);
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::where('is_read', false)->count(),
            'critical' => Notification::where('level', 'critical')->where('is_read', false)->count(),
        ];
        return view('notifications.index', compact('notifications', 'stats'));
    }

    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json(['success' => true]);
    }
}
