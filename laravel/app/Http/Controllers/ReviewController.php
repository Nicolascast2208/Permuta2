<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Chat $chat)
    {
        $otherUser = $chat->user1_id === auth()->id() 
            ? $chat->user2 
            : $chat->user1;
            
        return view('reviews.create', [
            'chat' => $chat,
            'otherUser' => $otherUser
        ]);
    }

public function store(Request $request, Chat $chat)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500'
    ]);

    // Obtener el ID del usuario revisado
    $otherUserId = $chat->user1_id === auth()->id() 
        ? $chat->user2_id 
        : $chat->user1_id;

    // Crear la reseña
    Review::create([
        'author_id' => auth()->id(),
        'reviewed_user_id' => $otherUserId,
        'rating' => $request->rating,
        'comment' => $request->comment
    ]);
    
    // Actualizar el rating del usuario revisado
    $otherUser = User::find($otherUserId); // Obtener instancia del usuario
    $otherUser->updateRating(); // Actualizar su rating
    
    // Marcar como completado en el chat
    $chat->markCompletedByUser(auth()->id());

    return redirect()->route('chat.show', $chat)
        ->with('success', '¡Reseña enviada!');
}
}