<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotificationController extends Controller
{
    use AuthorizesRequests;
    
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);
        
        $notification->update(['read' => true]);
        return back()->with('success', 'Notificación marcada como leída');
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->update(['read' => true]);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas');
    }

    // Método para marcar múltiples notificaciones como leídas
public function markMultipleAsRead(Request $request)
{
    \Log::info('MarkMultipleAsRead called', [
        'user_id' => auth()->id(),
        'notification_ids' => $request->input('notification_ids', [])
    ]);
    
    $notificationIds = $request->input('notification_ids', []);
    
    if (!empty($notificationIds)) {
        $userNotifications = auth()->user()->notifications()
            ->whereIn('id', $notificationIds)
            ->where('read', false)
            ->get();
        
        \Log::info('Notifications found', [
            'count' => $userNotifications->count(),
            'notification_ids' => $userNotifications->pluck('id')->toArray()
        ]);
        
        $updatedCount = 0;
        foreach ($userNotifications as $notification) {
            $notification->update(['read' => true]);
            $updatedCount++;
        }
        
        \Log::info('Notifications updated', ['count' => $updatedCount]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notificaciones marcadas como leídas',
            'count' => $updatedCount
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'No se proporcionaron notificaciones'
    ]);
}
}