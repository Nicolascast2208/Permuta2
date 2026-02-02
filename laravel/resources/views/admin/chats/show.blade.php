{{-- resources/views/admin/chats/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Chat')
@section('subtitle', 'Conversación entre usuarios')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.chats.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Chats</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    @if(!$chat->is_closed)
    <form action="{{ route('admin.chats.close', $chat) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-lock mr-2"></i> Cerrar Chat
        </button>
    </form>
    @endif
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Información del chat -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Chat</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    <!-- Usuarios -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Usuarios</h4>
                        <div class="space-y-2">
                            <div class="flex items-center p-2 border border-gray-200 rounded">
                                <img class="h-8 w-8 rounded-full mr-2" src="{{ $chat->user1->profile_photo_url }}" alt="{{ $chat->user1->name }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $chat->user1->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $chat->user1->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center p-2 border border-gray-200 rounded">
                                <img class="h-8 w-8 rounded-full mr-2" src="{{ $chat->user2->profile_photo_url }}" alt="{{ $chat->user2->name }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $chat->user2->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $chat->user2->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Oferta relacionada -->
                    @if($chat->offer)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Oferta Relacionada</h4>
                        <a href="{{ route('admin.offers.show', $chat->offer) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            Oferta #{{ $chat->offer->id }}
                        </a>
                    </div>
                    @endif

                    <!-- Estadísticas -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Estadísticas</h4>
                        <dl class="space-y-1">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Total mensajes</dt>
                                <dd class="text-sm text-gray-900">{{ $chat->messages->count() }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Estado</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chat->is_closed ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $chat->is_closed ? 'Cerrado' : 'Abierto' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Creado</dt>
                                <dd class="text-sm text-gray-900">{{ $chat->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    <div class="lg:col-span-3">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Mensajes</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @foreach($chat->messages->sortBy('created_at') as $message)
                    <div class="flex {{ $message->user_id == $chat->user1->id ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs lg:max-w-md rounded-lg px-4 py-2 {{ $message->user_id == $chat->user1->id ? 'bg-gray-100' : 'bg-indigo-100' }}">
                            <div class="flex items-center mb-1">
                                <img class="h-6 w-6 rounded-full mr-2" src="{{ $message->user->profile_photo_url }}" alt="{{ $message->user->name }}">
                                <span class="text-sm font-medium text-gray-900">{{ $message->user->name }}</span>
                            </div>
                            <p class="text-sm text-gray-900">{{ $message->body }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($chat->messages->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-comments text-gray-300 text-4xl mb-2"></i>
                    <p class="text-gray-500">No hay mensajes en este chat</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection