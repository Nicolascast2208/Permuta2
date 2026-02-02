<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\Message;

class ChatComponent extends Component
{
    use WithFileUploads;

    public $chat;
    public $otherUser;
    public $newMessage = '';
    public $messages = [];
    public $attachment;

    public function mount($chat, $otherUser)
    {
        $this->chat = $chat;
        $this->otherUser = $otherUser;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->chat->messages()
            ->with('user')
            ->orderBy('created_at', 'asc') // Orden ascendente para mostrar correctamente
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'body' => $message->body, // Cambiado a 'body'
                    'created_at' => $message->created_at->format('d/m/Y H:i'),
                    'user' => [
                        'alias' => $message->user->name,
                        'profile_photo_url' => $message->user->profile_photo_url,
                    ],
                ];
            })
            ->toArray();
    }

    public function sendMessage()
    {
            if ($this->chat->is_closed) {
        return;
    }
        $this->validate([
            'newMessage' => 'required_without:attachment|string|max:1000'
        ]);
        
        if ($this->newMessage) {
            // Usar 'body' en lugar de 'content'
            $this->chat->messages()->create([
                'user_id' => auth()->id(),
                'body' => $this->newMessage // Campo corregido
            ]);
        }
        
        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('message-sent');
    }
public function completeTrade()
{
    $this->chat->markCompletedByUser(auth()->id());
    
    if ($this->chat->is_closed) {
        $this->dispatch('chat-closed');
    } else {
        session()->flash('message', 'Esperando que el otro usuario complete el trueque...');
    }
    
    $this->dispatch('scroll-bottom');
}
    #[On('message-sent')]
    public function scrollToBottom()
    {
        $this->dispatch('scroll-bottom');
    }

    public function render()
    {
        return view('livewire.chat-component');
    }


}