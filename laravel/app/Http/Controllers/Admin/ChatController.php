<?php
// app/Http/Controllers/Admin/ChatController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $query = Chat::with(['user1', 'user2', 'offer']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user1', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('user2', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('status')) {
            if ($request->status == 'open') {
                $query->where('is_closed', false);
            } elseif ($request->status == 'closed') {
                $query->where('is_closed', true);
            }
        }

        $chats = $query->latest()->paginate(20);

        return view('admin.chats.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        $chat->load(['user1', 'user2', 'offer', 'messages.user']);
        return view('admin.chats.show', compact('chat'));
    }

    public function close(Chat $chat)
    {
        $chat->update(['is_closed' => true]);

        return redirect()->back()->with('success', 'Chat cerrado correctamente.');
    }

    public function messages(Chat $chat)
    {
        $messages = $chat->messages()->with('user')->latest()->paginate(50);
        return response()->json($messages);
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();

        return redirect()->route('admin.chats.index')
            ->with('success', 'Chat eliminado correctamente.');
    }
}