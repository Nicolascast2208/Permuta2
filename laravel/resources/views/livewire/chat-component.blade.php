<div 
    class="flex flex-col h-[500px]"
    x-data="{
        init() {
            this.$nextTick(() => this.scrollToBottom());
            Livewire.on('scroll-bottom', () => {
                this.scrollToBottom();
            });
        },
        scrollToBottom() {
            const container = this.$refs.messages;
            container.scrollTop = container.scrollHeight;
        }
    }"
>
    <!-- Área de mensajes -->
    <div 
        class="flex-1 overflow-y-auto p-4 bg-gray-50" 
        x-ref="messages"
        wire:poll.5s="loadMessages"
    >
        @foreach ($messages as $message)
            <div class="mb-4 flex {{ $message['user_id'] === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs md:max-w-md px-4 py-2 rounded-lg 
                    {{ $message['user_id'] === auth()->id() ? 'bg-blue-500 text-white' : 'bg-white border border-gray-200' }}">
                    <!-- Cabecera del mensaje -->
                    <div class="flex items-center mb-1">
                        <div class="w-6 h-6 rounded-full overflow-hidden mr-2">
                            @if($message['user_id'] === auth()->id())
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="Tu foto" class="w-full h-full object-cover">
                            @else
                                <img src="{{ $message['user']['profile_photo_url'] }}" alt="{{ $message['user']['alias'] }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <span class="text-xs font-medium {{ $message['user_id'] === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                            {{ $message['user_id'] === auth()->id() ? 'Tú' : $message['user']['alias'] }}
                        </span>
                    </div>
                    
                    <!-- Contenido del mensaje -->
                    <p class="break-words">{{ $message['body'] }}</p> <!-- Cambiado a 'body' -->
                    
                    <!-- Pie del mensaje -->
                    <p class="text-xs mt-1 {{ $message['user_id'] === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                        {{ $message['created_at'] }}
                    </p>
                </div>
            </div>
        @endforeach
        
        @if(empty($messages))
            <div class="h-full flex flex-col items-center justify-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p>Envía un mensaje para coordinar tu permuta</p>
            </div>
        @endif
    </div>
    
    <!-- Entrada de mensajes -->
    <div class="border-t p-4 bg-white">
        <form wire:submit.prevent="sendMessage" class="flex">
            <input 
                type="text" 
                wire:model="newMessage"
                wire:keydown.enter.prevent="sendMessage"
                placeholder="Escribe un mensaje..."
                class="flex-1 px-4 py-2 border rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            <button 
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition"
            >
              <svg xmlns="http://www.w3.org/2000/svg" 
         class="h-5 w-5 rotate-90" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke="currentColor">
        <path stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
    </svg>
            </button>
        </form>
    </div>
</div>