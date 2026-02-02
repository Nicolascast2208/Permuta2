@extends('layouts.app')

@section('title', 'Mis Permutas')

@section('content')
<div class="container mx-auto px-4 py-8 fondo-gris rounded-xl">
    <div class="mb-6 text-center md:text-left">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Mis Permutas</h1>
        <p class="text-gray-600">Aquí puedes ver todos tus permutas completadas</p>
    </div>
    
    @if ($trades->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes permutas aún</h3>
            <p class="mt-1 text-gray-500">Cuando aceptes una permuta, aparecerá aquí.</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 transition ease-in-out duration-150">
                    Explorar productos
                </a>
            </div>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="grid grid-cols-1 divide-y">
                @foreach ($trades as $trade)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            
                            <!-- Información de la permuta -->
                            <div class="flex-1">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                                    
                                    <!-- Producto recibido -->
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            @if($trade->productToReceive->images->count() > 0)
                                                <img src="{{ asset('storage/' . $trade->productToReceive->images->first()->path) }}" 
                                                    alt="{{ $trade->productToReceive->title }}"
                                                    class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            @else
                                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                                            @endif
                                            <div class="ml-4">
                                                <h4 class="font-medium text-gray-900">Recibiste</h4>
                                                <a href="{{ route('products.show', $trade->productToReceive) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                    {{ $trade->productToReceive->title }}
                                                </a>
                                           
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Icono de intercambio -->
                                    <div class="flex items-center justify-center mx-auto sm:mx-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Producto dado -->
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            @if($trade->productToGive->images->count() > 0)
                                                <img src="{{ asset('storage/' . $trade->productToGive->images->first()->path) }}" 
                                                    alt="{{ $trade->productToGive->title }}"
                                                    class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            @else
                                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                                            @endif
                                            <div class="ml-4">
                                                <h4 class="font-medium text-gray-900">Diste</h4>
                                                <a href="{{ route('products.show', $trade->productToGive) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                    {{ $trade->productToGive->title }}
                                                </a>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center text-sm text-gray-500 flex-wrap gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Permuta completada el {{ $trade->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            
                            <!-- Acciones -->
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
    @if ($trade->chat && $trade->chat->is_closed)
        <!-- Botón de permuta completada (solo visual) -->
        <span class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest">
            Permuta completada
        </span>
    @else
        <!-- Botones normales para chats activos -->
        @if ($trade->chat)
            <a href="{{ route('chat.show', $trade->chat) }}" 
               class="flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:ring ring-blue-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Ver Chat
            </a>
        @endif
        
        <a href="{{ route('products.show', $trade->productToReceive) }}" 
           class="flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-full font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:ring ring-gray-300 transition">
            Ver Detalle
        </a>
    @endif
</div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="bg-white px-6 py-4 border-t">
                {{ $trades->links('components.pagination') }}
            </div>
        </div>
    @endif
</div>
@endsection