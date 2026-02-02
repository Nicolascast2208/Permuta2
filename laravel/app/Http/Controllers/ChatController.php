<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ChatController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);
        
        // Cargar relaciones necesarias
        $chat->load([
            'offer.productsOffered.images', // Cambiado de productOffered a productsOffered
            'offer.productRequested.images',
            'offer.fromUser',
            'offer.toUser',
            'messages.user'
        ]);
        
        $otherUser = $chat->user1_id === auth()->id() 
            ? $chat->user2 
            : $chat->user1;
        
        // Determinar qué productos mostrar según el rol del usuario
        $user = auth()->user();
        $offer = $chat->offer;
        
        // Si el usuario actual es quien hizo la oferta
        if ($user->id == $offer->from_user_id) {
            $productToReceive = $offer->productRequested;
            $productToGive = $offer->productsOffered->first(); // Primer producto ofrecido
        } 
        // Si el usuario actual es quien recibió la oferta
        else {
            $productToReceive = $offer->productsOffered->first(); // Primer producto ofrecido
            $productToGive = $offer->productRequested;
        }
        
        return view('chat.show', [
            'chat' => $chat,
            'otherUser' => $otherUser,
            'productToReceive' => $productToReceive,
            'productToGive' => $productToGive
        ]);
    }
    
    public function sendMessage(Request $request, Chat $chat)
    {
        $this->authorize('view', $chat);
        
        $request->validate(['body' => 'required|string|max:1000']);
        
        $chat->messages()->create([
            'user_id' => Auth::id(),
            'body' => $request->content
        ]);
        
        return back();
    }
    
    public function completeTrade(Chat $chat)
    {
        $this->authorize('update', $chat);
        
        $chat->markCompletedByUser(auth()->id());
        
        if ($chat->is_closed) {
            return redirect()->route('reviews.create', $chat);
        }
        
        return back()->with('message', 'Esperando confirmación del otro usuario...');
    }
}