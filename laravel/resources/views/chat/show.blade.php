@extends('layouts.app')

@section('title', 'Chat de Permuta')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Encabezado del chat -->
                <div class="border-b p-6 bg-white">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                        <div class="flex items-center">
                            <a href="{{ route('user.profile', $otherUser) }}" class="flex items-center">
                                <img src="{{ $otherUser->profile_photo_url }}" 
                                     alt="{{ $otherUser->alias }}" 
                                     class="w-12 h-12 rounded-full mr-4 object-cover border-2 border-gray-200">
                                <span class="font-semibold text-lg hover:underline">{{ $otherUser->name }}</span>
                            </a>
                        </div>
                        
                        @if(!$chat->is_closed)
                            @if(($chat->user1_id == auth()->id() && !$chat->completed_by_user1) || 
                                ($chat->user2_id == auth()->id() && !$chat->completed_by_user2))
                              <form action="{{ route('chats.complete', $chat) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition font-medium shadow-md hover:shadow-lg">
                                    Completar Permuta
                                </button>
                              </form>
                            @else
                                <span class="text-green-600 font-medium px-4 py-2 bg-green-50 rounded-full">
                                    Esperando confirmación del otro usuario
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-5 py-3 bg-green-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest shadow-md">
                                Permuta completada
                            </span>
                        @endif
                    </div>
                    
                    <!-- Resumen de la permuta -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Producto que recibes -->
                        <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-100 shadow-sm">
                            @if($productToReceive && $productToReceive->images->count() > 0)
                                <img src="{{ asset('storage/' . $productToReceive->images->first()->path) }}" 
                                    alt="{{ $productToReceive->title }}"
                                    class="w-20 h-20 object-cover rounded-lg shadow-sm">
                            @else
                                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl w-20 h-20 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="ml-5">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Recibes:</p>
                                @if($productToReceive)
                                    <a href="{{ route('products.show', $productToReceive) }}" class="font-semibold text-blue-700 hover:text-blue-900 transition block mt-1 line-clamp-1">
                                        {{ $productToReceive->title }}
                                    </a>
                                @else
                                    <span class="text-red-500">Producto no disponible</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Producto que das -->
                        <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-100 shadow-sm">
                            @if($productToGive && $productToGive->images->count() > 0)
                                <img src="{{ asset('storage/' . $productToGive->images->first()->path) }}" 
                                    alt="{{ $productToGive->title }}"
                                    class="w-20 h-20 object-cover rounded-lg shadow-sm">
                            @else
                                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl w-20 h-20 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="ml-5">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Diste:</p>
                                @if($productToGive)
                                    <a href="{{ route('products.show', $productToGive) }}" class="font-semibold text-blue-700 hover:text-blue-900 transition block mt-1 line-clamp-1">
                                        {{ $productToGive->title }}
                                    </a>
                                @else
                                    <span class="text-red-500">Producto no disponible</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional para múltiples productos -->
                    @if($chat->offer->productsOffered->count() > 1)
                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">
                                        Esta permuta incluye {{ $chat->offer->productsOffered->count() }} productos ofrecidos.
                                    </p>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Estás viendo el producto principal. Coordina los detalles de todos los productos en el chat.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Componente de chat o mensaje de chat cerrado -->
                <div class="p-1">
                    @if($chat->is_closed)
                        <!-- Mensaje cuando el chat está cerrado -->
                        <div class="p-8 text-center">
                            <div class="bg-gray-50 rounded-xl p-8 max-w-md mx-auto border border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">Chat Cerrado</h3>
                                <p class="text-gray-500 mb-6">Esta permuta ha sido completada. El chat ya no está disponible.</p>
                                <a 
                                    href="{{ route('dashboard') }}" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Volver a mi Perfil
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Componente de chat activo -->
                        <livewire:chat-component :chat="$chat" :otherUser="$otherUser" />
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Columna derecha: Información del usuario -->
        <div class="lg:col-span-1">
            <div class="fondo-gris rounded-xl shadow-md overflow-hidden sticky top-6 border border-gray-100">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <a href="{{ route('user.profile', $otherUser) }}" class="block relative mb-5">
                            <img src="{{ $otherUser->profile_photo_url }}" 
                                 alt="{{ $otherUser->alias }}"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                        </a>
                        <a href="{{ route('user.profile', $otherUser) }}" class="block mb-1">
                            <h3 class="text-xl font-bold text-gray-900 text-center">{{ $otherUser->name }}</h3>
                        </a>
                        <p class="text-sm text-gray-500 mb-6">Miembro desde {{ $otherUser->created_at->format('d/m/Y') }}</p>
                        
                        <!-- Rating -->
                        <div class="mb-6 w-full bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex flex-col items-center">
                                <div class="flex text-yellow-400 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($otherUser->rating))
                                            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-lg font-semibold text-gray-700">{{ number_format($otherUser->rating, 1) }}/5.0</span>
                            </div>
                        </div>
                        
                        <!-- Ubicación -->
                        <div class="flex items-center text-gray-600 bg-white rounded-lg p-3 w-full shadow-sm">
                            <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm font-medium">{{ $otherUser->location ?? 'Ubicación no especificada' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection