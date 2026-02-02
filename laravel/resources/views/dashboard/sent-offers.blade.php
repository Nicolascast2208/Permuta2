@extends('layouts.app')

@section('title', 'Ofertas Enviadas')
@section('content')
<div class="container mx-auto px-4 py-8 fondo-gris rounded-xl">
    <!-- Header y Contador -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ofertas Enviadas</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $offers->total() }} ofertas enviadas</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl" role="alert">
            <p class="font-bold">¡Éxito!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-xl" role="alert">
            <p class="font-bold">Información</p>
            <p>{{ session('info') }}</p>
        </div>
    @endif

    <!-- Tabs principales para estados - CON PESTAÑA SEPARADA PARA PENDIENTES DE PAGO -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2 mb-4">
            <!-- Pendientes (solo pending) -->
            <a href="{{ route('dashboard.sent-offers', ['status' => 'pending', 'filter' => $filter, 'sort' => $sort]) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status == 'pending' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                Pendientes ({{ $counters['pending'] ?? 0 }})
            </a>
            <!-- Pendientes de Pago (waiting_payment) -->
            <a href="{{ route('dashboard.sent-offers', ['status' => 'waiting_payment', 'filter' => $filter, 'sort' => $sort]) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status == 'waiting_payment' ? 'bg-yellow-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                Pendientes de Pago ({{ $counters['waiting_payment'] ?? 0 }})
            </a>
            <!-- Aceptadas -->
            <a href="{{ route('dashboard.sent-offers', ['status' => 'accepted', 'filter' => $filter, 'sort' => $sort]) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status == 'accepted' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                Aceptadas ({{ $counters['accepted'] ?? 0 }})
            </a>
            <!-- Rechazadas -->
            <a href="{{ route('dashboard.sent-offers', ['status' => 'rejected', 'filter' => $filter, 'sort' => $sort]) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status == 'rejected' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                Rechazadas ({{ $counters['rejected'] ?? 0 }})
            </a>
        </div>

        <!-- Filtros de match y ordenamiento ACTUALIZADOS -->
        <div class="flex flex-wrap gap-3 items-center bg-white p-4 rounded-lg border border-gray-200">
            <span class="text-sm font-medium text-gray-700">Ordenar por:</span>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard.sent-offers', ['status' => $status, 'filter' => 'match_general', 'sort' => 'desc']) }}" 
                   class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $filter == 'match_general' && $sort == 'desc' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    🎯 Mejor Match
                </a>
                
                <a href="{{ route('dashboard.sent-offers', ['status' => $status, 'filter' => 'price', 'sort' => 'desc']) }}" 
                   class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $filter == 'price' && $sort == 'desc' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    💰 Mayor Valor
                </a>

                <!-- NUEVO FILTRO: Por Interés -->
                <a href="{{ route('dashboard.sent-offers', ['status' => $status, 'filter' => 'interest', 'sort' => 'desc']) }}" 
                   class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $filter == 'interest' && $sort == 'desc' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    🔥 Por Interés
                </a>
            </div>
        </div>
    </div>

    <!-- Banner informativo para Pendientes de Pago -->
    @if($status == 'waiting_payment' && ($counters['waiting_payment'] ?? 0) > 0)
    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="font-semibold text-yellow-800">Ofertas Pendientes de Pago</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Estas ofertas han sido aceptadas pero necesitas pagar la comisión para completar el trueque.
                    Una vez que realices el pago, el trueque se completará automáticamente.
                </p>
            </div>
        </div>
    </div>
    @endif

    @if ($offers->isEmpty())
        <!-- Mensaje cuando no hay ofertas -->
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            
            @if($status == 'pending')
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes ofertas pendientes</h3>
            <p class="mt-1 text-gray-500">Las ofertas que envíes aparecerán aquí mientras esperas respuesta.</p>
            @elseif($status == 'waiting_payment')
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay ofertas pendientes de pago</h3>
            <p class="mt-1 text-gray-500">Las ofertas aceptadas que requieran pago aparecerán aquí.</p>
            @elseif($status == 'accepted')
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes ofertas aceptadas</h3>
            <p class="mt-1 text-gray-500">Las ofertas que sean aceptadas aparecerán aquí.</p>
            @elseif($status == 'rejected')
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes ofertas rechazadas</h3>
            <p class="mt-1 text-gray-500">Las ofertas que sean rechazadas aparecerán aquí.</p>
            @endif
            
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 transition ease-in-out duration-150">
                    Explorar productos
                </a>
            </div>
        </div>
    @else
        <!-- Lista de ofertas enviadas -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="grid grid-cols-1 divide-y">
                @foreach ($offers as $offer)
                @php
                    $productsOfferedCount = $offer->productsOffered->count();
                    $matchScore = $offer->match_score ?? 0;
                    $totalValue = $offer->total_offered_value ?? 0;
                    $interestScore = $offer->interest_score ?? 0;
                @endphp
                
                <div class="p-6 hover:bg-gray-50 transition 
                    @if($offer->status === 'accepted') bg-green-50 @endif
                    @if($offer->status === 'waiting_payment') bg-yellow-50 @endif">
                    
                    <!-- Banner para ofertas en waiting_payment -->
                    @if($offer->status === 'waiting_payment')
                    <div class="mb-4 p-3 bg-yellow-100 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-yellow-700 font-medium">
                                ✅ Oferta aceptada - Debes pagar la comisión para completar el trueque
                            </span>
                        </div>
                    </div>
                    @endif

                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        
                        <!-- Información de la oferta -->
                        <div class="flex-1">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                                
                                <!-- Tu producto (el que ofreciste) -->
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        @if($productsOfferedCount > 0)
                                            <div class="relative">
                                                @if($offer->productsOffered->first() && $offer->productsOffered->first()->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $offer->productsOffered->first()->images->first()->path) }}" 
                                                        alt="{{ $offer->productsOffered->first()->title }}"
                                                        class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                                @else
                                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                                                @endif
                                                
                                                @if($productsOfferedCount > 1)
                                                    <div class="absolute -top-2 -right-2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                                                        +{{ $productsOfferedCount - 1 }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                                        @endif
                                        
                                        <div class="ml-4">
                                            <h4 class="font-medium text-gray-900">Tu producto</h4>
                                            @if($productsOfferedCount > 0)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-blue-600">
                                                        {{ $offer->productsOffered->first()->title ?? 'Producto' }}
                                                    </span>
                                                    @if($productsOfferedCount > 1)
                                                        <span class="text-xs text-gray-500">+ {{ $productsOfferedCount - 1 }} más</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500">Condición: {{ $offer->productsOffered->first()->condition_name ?? '-' }}</p>
                                            @else
                                                <span class="text-sm text-red-500">Producto no disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Icono de oferta (flecha hacia otro usuario) -->
                                <div class="flex items-center justify-center mx-auto sm:mx-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                    </svg>
                                </div>
                                
                                <!-- Producto deseado -->
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        @if($offer->productRequested && $offer->productRequested->images->count() > 0)
                                            <img src="{{ asset('storage/' . $offer->productRequested->images->first()->path) }}" 
                                                alt="{{ $offer->productRequested->title }}"
                                                class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                        @else
                                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                                        @endif
                                        <div class="ml-4">
                                            <h4 class="font-medium text-gray-900">Deseas obtener</h4>
                                            @if($offer->productRequested)
                                                <a href="{{ route('products.show', $offer->productRequested) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                    {{ $offer->productRequested->title }}
                                                </a>
                                                <p class="text-xs text-gray-500">De: {{ $offer->toUser->name ?? 'Usuario desconocido' }}</p>
                                            @else
                                                <span class="text-sm text-red-500">Producto no disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="flex flex-col gap-2 mt-4">
                                    @if ($offer->status === 'pending')
                                        <!-- Preguntas intermedias para ofertas pendientes -->
                                        <a href="{{ route('offer.intermediate', $offer) }}" 
                                           class="inline-flex items-center justify-center px-3 py-2 bg-red-200 border border-transparent rounded-full font-semibold text-xs texto-naranjo uppercase tracking-widest hover:bg-red-300 active:bg-red-900 focus:ring ring-red-300 transition">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#F54D26" class="h-5 w-5 mr-2">
                                            <path d="M256 32C114.6 32 0 125.1 0 240c0 47.6 21.4 91.2 56.8 126.2C49.4 419 32 480 32 480s85.6-18.4 145.1-55.3c24.7 5.1 50.7 7.8 78.9 7.8 141.4 0 256-93.1 256-208S397.4 32 256 32z"/>
                                          </svg>
                                            <span>ver chat</span>
                                        </a>

                                    @elseif ($offer->status === 'waiting_payment')
                                        <!-- El usuario que hizo la oferta debe pagar -->
                                        <a href="{{ route('checkout.commission-offered', $offer) }}" 
                                           class="flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:ring ring-yellow-300 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Pagar Comisión</span>
                                        </a>
                                        
                                        <!-- Preguntas intermedias siempre disponibles -->
                                        <a href="{{ route('offer.intermediate', $offer) }}" 
                                           class="inline-flex items-center justify-center px-3 py-2 bg-red-200 border border-transparent rounded-full font-semibold text-xs texto-naranjo uppercase tracking-widest hover:bg-red-300 active:bg-red-900 focus:ring ring-red-300 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#F54D26" class="h-5 w-5 mr-2">
                                              <path d="M256 32C114.6 32 0 125.1 0 240c0 47.6 21.4 91.2 56.8 126.2C49.4 419 32 480 32 480s85.6-18.4 145.1-55.3c24.7 5.1 50.7 7.8 78.9 7.8 141.4 0 256-93.1 256-208S397.4 32 256 32z"/>
                                            </svg>
                                            <span>ver chat</span>
                                        </a>

                                    @elseif ($offer->status === 'accepted' && $offer->chat)
                                        <!-- Oferta aceptada - Ir al chat -->
                                        <a href="{{ route('chat.show', $offer->chat) }}" 
                                           class="flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:ring ring-blue-300 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span>Ir al Chat</span>
                                        </a>
                                    @endif
                                    <button onclick="openOfferModal({{ $offer->id }})" 
                                            class="inline-flex items-center justify-center px-3 py-2 bg-green-100 border border-transparent rounded-full font-semibold text-xs text-green-800 uppercase tracking-widest hover:bg-green-500 active:bg-green-900 focus:ring ring-green-300 transition"
                                            title="Ver oferta">
                                        VER OFERTA
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Información adicional: Match score, valor, estado -->
                            <div class="mt-4 flex flex-wrap items-center gap-4">
                                <!-- Match Score -->
                                <div class="flex items-center">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-700">Match:</span>
                                        <div class="flex items-center space-x-1">
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $matchScore }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-gray-600">{{ $matchScore }}%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Score de Interés (solo si está disponible) -->
                                @if($interestScore > 0 && $filter == 'interest')
                                <div class="flex items-center">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-700">Interés:</span>
                                        <div class="flex items-center space-x-1">
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ min($interestScore, 100) }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-gray-600">{{ $interestScore }}%</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Valor total ofrecido -->
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ number_format($totalValue, 0, ',', '.') }} CLP</span>
                                </div>

                                <!-- Fecha -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $offer->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <!-- Estado -->
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($offer->status === 'accepted') bg-green-100 text-green-800
                                    @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($offer->status === 'waiting_payment') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $offer->status_name }}
                                </span>

                                @if($offer->status === 'pending')
                                    <span class="text-xs text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Esperando respuesta
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="bg-white px-6 py-4 border-t">
                {{ $offers->links('components.pagination') }}
            </div>
        </div>
    @endif
</div>

<!-- Modal para ver oferta - USANDO EL DISEÑO DEL PRIMER BLADE -->
<div id="offer-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4 hidden backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        <!-- Header fijo -->
        <div class="sticky top-0 bg-green-600 border-b border-gray-200 px-6 py-4 z-10">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-white">Detalles de la Oferta</h3>
                    <p class="text-sm text-white mt-1" id="modal-subtitle">Oferta enviada - Esperando respuesta</p>
                </div>
                <button id="close-modal" class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200 group">
                    <svg class="w-6 h-6 text-white group-hover:text-gray-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Contenido scrollable -->
        <div id="modal-content" class="p-6 overflow-y-auto max-h-[calc(95vh-80px)]">
            <!-- Contenido cargado dinámicamente -->
        </div>
    </div>
</div>

<!-- Scripts para el modal - ADAPTADO PARA OFERTAS ENVIADAS -->
<script>
// Función para formatear moneda chilena
function formatChileanCurrency(amount) {
    if (!amount) amount = 0;
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Función para obtener color del estado
function getStatusColor(status) {
    const colors = {
        'pending': { bg: 'bg-blue-50', text: 'text-blue-700', border: 'border-blue-200' },
        'accepted': { bg: 'bg-green-50', text: 'text-green-700', border: 'border-green-200' },
        'rejected': { bg: 'bg-red-50', text: 'text-red-700', border: 'border-red-200' },
        'waiting_payment': { bg: 'bg-yellow-50', text: 'text-yellow-700', border: 'border-yellow-200' }
    };
    return colors[status] || colors.pending;
}

// Función para animar la apertura del modal
function animateModalOpen(modal) {
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
        modal.querySelector('.bg-white').classList.add('scale-100', 'opacity-100');
    }, 50);
}

// Función para animar el cierre del modal
function animateModalClose(modal, callback) {
    modal.querySelector('.bg-white').classList.remove('scale-100', 'opacity-100');
    modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-0');
    modal.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.opacity = '';
        if (callback) callback();
    }, 300);
}

// Función para abrir modal de oferta
function openOfferModal(offerId) {
    const modal = document.getElementById('offer-modal');
    const modalContent = document.getElementById('modal-content');
    
    // Mostrar loading mejorado
    modalContent.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16">
            <div class="relative">
                <div class="w-12 h-12 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-gray-600 font-medium">Cargando detalles de la oferta...</p>
            <p class="text-sm text-gray-400 mt-1">Preparando información completa</p>
        </div>
    `;
    
    animateModalOpen(modal);
    
    // Cargar datos de la oferta
    fetch(`/offers/${offerId}/details`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(offer => {
            renderSentOfferModal(offer, modalContent);
        })
        .catch(error => {
            console.error('Error loading offer details:', error);
            modalContent.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Error al cargar</h4>
                    <p class="text-gray-500 mb-6">No se pudieron cargar los detalles de la oferta</p>
                    <button onclick="openOfferModal(${offerId})" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Reintentar
                    </button>
                </div>
            `;
        });
}

// Función para renderizar el contenido del modal PARA OFERTAS ENVIADAS
function renderSentOfferModal(offer, modalContent) {
    const statusColor = getStatusColor(offer.status);
    
    // VALORES CORREGIDOS - Evitamos NaN y valores undefined
    const totalValue = offer.total_offered_value || 0;
    const matchScore = offer.match_score || 0;
    const productRequestedValue = offer.product_requested?.price_reference || 0;
    const complementaryAmount = offer.complementary_amount || 0;
    
    // Cálculo de diferencia con validación
    const difference = (totalValue + complementaryAmount) - productRequestedValue;
    
    // Actualizar subtítulo
    document.getElementById('modal-subtitle').textContent = 
        `Oferta enviada a ${offer.to_user?.name || 'Usuario'} - ${new Date(offer.created_at).toLocaleDateString('es-CL')}`;

    modalContent.innerHTML = `
        <!-- Header de resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Estado -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Estado de la oferta</span>
                    <div class="px-3 py-1 rounded-full text-xs font-medium ${statusColor.bg} ${statusColor.text} ${statusColor.border}">
                        ${offer.status_name || 'Pendiente'}
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 mt-2">${offer.status_name || 'Pendiente'}</p>
            </div>

            <!-- Match Score -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Match</span>
                    <svg class="w-4 h-4 ${matchScore > 0 ? 'text-green-600' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex items-end justify-between mt-2">
                    <span class="text-2xl font-bold ${matchScore > 0 ? 'text-gray-900' : 'text-gray-400'}">${matchScore}%</span>
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-1000 ${matchScore > 0 ? 'bg-green-500' : 'bg-gray-300'}" style="width: ${Math.max(matchScore, 5)}%"></div>
                    </div>
                </div>
                ${matchScore === 0 ? '<p class="text-xs text-gray-500 mt-1">Sin datos de match</p>' : ''}
            </div>

            <!-- Valor Total -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Valor Total</span>
                    <svg class="w-4 h-4 ${totalValue > 0 ? 'text-blue-600' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-2xl font-bold ${totalValue > 0 ? 'text-gray-900' : 'text-gray-400'}">${formatChileanCurrency(totalValue)}</p>
                ${complementaryAmount > 0 ? `
                    <p class="text-xs text-green-600 mt-1">+${formatChileanCurrency(complementaryAmount)} complementario</p>
                ` : ''}
                ${totalValue === 0 ? '<p class="text-xs text-gray-500 mt-1">Valor no especificado</p>' : ''}
            </div>
        </div>

        <!-- Grid principal -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Tus Productos Ofrecidos -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-gray-900 text-lg flex items-center">
                        <span class="w-2 h-6 bg-blue-600 rounded-full mr-3"></span>
                        Tus Productos Ofrecidos
                        <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">${offer.products_offered?.length || 0}</span>
                    </h4>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">ENTREGAS</span>
                </div>
                
                <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                    ${(offer.products_offered && offer.products_offered.length > 0) ? 
                        offer.products_offered.map((product, index) => `
                            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow group">
                                <div class="flex space-x-4">
                                    <a href="/productos/${product.id}" class="block flex-shrink-0 relative">
                                        <img src="${product.first_image_url || '/placeholder-image.jpg'}" 
                                             alt="${product.title || 'Producto'}"
                                             class="w-16 h-16 object-cover rounded-lg group-hover:scale-105 transition-transform">
                                        <div class="absolute -top-2 -right-2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                                            ${index + 1}
                                        </div>
                                    </a>
                                    <div class="flex-1 min-w-0">
                                        <a href="/productos/${product.id}" class="block hover:text-blue-600 transition-colors">
                                            <h5 class="font-medium text-gray-900 truncate">${product.title || 'Producto'}</h5>
                                        </a>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">${product.description || 'Sin descripción'}</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-sm font-semibold ${product.price_reference > 0 ? 'text-green-600' : 'text-gray-500'}">
                                                ${formatChileanCurrency(product.price_reference || 0)}
                                            </span>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                ${product.condition_name || 'Condición no especificada'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('') 
                        : `
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-200">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-gray-500">No hay productos ofrecidos</p>
                        </div>
                    `}
                </div>
            </div>

            <!-- Producto que Deseas Obtener -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-gray-900 text-lg flex items-center">
                        <span class="w-2 h-6 bg-green-600 rounded-full mr-3"></span>
                        Producto que Deseas
                    </h4>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">RECIBES</span>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                    <div class="flex space-x-4">
                        <a href="/productos/${offer.product_requested?.id || '#'}" class="block flex-shrink-0 relative group">
                            <img src="${offer.product_requested?.first_image_url || '/placeholder-image.jpg'}" 
                                 alt="${offer.product_requested?.title || 'Producto no disponible'}"
                                 class="w-20 h-20 object-cover rounded-lg group-hover:scale-105 transition-transform">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all"></div>
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="/productos/${offer.product_requested?.id || '#'}" class="block hover:text-blue-600 transition-colors">
                                <h5 class="font-semibold text-gray-900 truncate">${offer.product_requested?.title || 'Producto no disponible'}</h5>
                            </a>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">${offer.product_requested?.description || 'Descripción no disponible'}</p>
                            <div class="mt-3 space-y-1">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium">Valor referencial: ${formatChileanCurrency(productRequestedValue)}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                   Estado:  ${offer.product_requested?.condition_name || 'Condición no especificada'}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                   Ubicación:  ${offer.product_requested?.location || 'Ubicación no especificada'}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804L13.16 12.49a2 2 0 002.24 0l7.658-5.313a1 1 0 00-1.16-1.626L14.24 10.86a2 2 0 00-2.24 0L5.12 16.178a1 1 0 101.12 1.626z" />
                                    </svg>
                                   Propietario:  ${offer.to_user?.name || 'Usuario desconocido'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        ${offer.comment ? `
        <!-- Tu Comentario -->
        <div class="mb-8">
            <h4 class="font-semibold text-gray-900 text-lg flex items-center mb-4">
                <span class="w-2 h-6 bg-purple-600 rounded-full mr-3"></span>
                Tu Mensaje
            </h4>
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-700 whitespace-pre-wrap">${offer.comment}</p>
                        <p class="text-sm text-purple-600 mt-2">— ${offer.from_user?.name || 'Tú'}</p>
                    </div>
                </div>
            </div>
        </div>
        ` : ''}

        <!-- Resumen del Intercambio -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200 mb-8">
            <h4 class="font-semibold text-gray-900 text-lg flex items-center mb-4">
                <span class="w-2 h-6 bg-orange-600 rounded-full mr-3"></span>
                Resumen del Intercambio
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Valor de tus productos:</span>
                        <span class="font-semibold text-gray-900">${formatChileanCurrency(totalValue)}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Valor del producto deseado:</span>
                        <span class="font-semibold text-gray-900">${formatChileanCurrency(productRequestedValue)}</span>
                    </div>
                    ${complementaryAmount > 0 ? `
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Monto adicional en efectivo:</span>
                        <span class="font-semibold text-green-600">+${formatChileanCurrency(complementaryAmount)}</span>
                    </div>
                    ` : ''}
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Match:</span>
                        <span class="font-semibold ${matchScore >= 80 ? 'text-green-600' : matchScore >= 60 ? 'text-yellow-600' : 'text-orange-600'}">
                            ${matchScore}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Fecha de oferta:</span>
                        <span class="font-semibold text-gray-900">${new Date(offer.created_at).toLocaleDateString('es-CL', { 
                            day: '2-digit', 
                            month: '2-digit', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Destinatario:</span>
                        <span class="font-semibold text-gray-900">${offer.to_user?.name || 'Usuario'}</span>
                    </div>
                </div>
            </div>
            ${(totalValue === 0 && matchScore === 0) ? `
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-700 text-center">
                        ℹ️ Esta oferta no tiene valores calculados. Considera revisar los productos manualmente.
                    </p>
                </div>
            ` : ''}
        </div>

        <!-- Acciones PARA OFERTAS ENVIADAS -->
        <div class="border-t border-gray-200 pt-6">
            <div class="space-y-4">
                ${offer.status === 'pending' ? `
                    <!-- Acciones para ofertas pendientes (enviadas) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Preguntas intermedias -->
                        <a href="/oferta/${offer.id}/intermediar" 
                           class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wider hover:from-orange-600 hover:to-orange-700 active:bg-orange-900 focus:ring-2 ring-orange-300 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="whitespace-nowrap">Preguntas Intermedias</span>
                        </a>

                        <!-- Cerrar modal -->
                        <button onclick="closeOfferModal()" 
                                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-gray-400 to-gray-500 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wider hover:from-gray-500 hover:to-gray-600 active:bg-gray-700 focus:ring-2 ring-gray-300 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="whitespace-nowrap">Cerrar</span>
                        </button>
                    </div>
                ` : ''}

                ${offer.status === 'accepted' && offer.chat ? `
                    <!-- Oferta aceptada - Ir al chat -->
                    <div class="text-center">
                        <a href="/chat/${offer.chat.id}" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wider hover:from-blue-600 hover:to-blue-700 active:bg-blue-900 focus:ring-2 ring-blue-300 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span class="whitespace-nowrap">Continuar en el Chat</span>
                        </a>
                    </div>
                ` : ''}

                ${offer.status === 'waiting_payment' ? `
                    <!-- El usuario que hizo la oferta debe pagar -->
                    <div class="text-center">
                        <a href="/checkout/commission-offered/${offer.id}" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-widest hover:from-yellow-600 hover:to-yellow-700 active:bg-yellow-900 focus:ring-2 ring-yellow-300 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="whitespace-nowrap">Pagar Comisión</span>
                        </a>
                        <p class="text-sm text-gray-600 mt-4">
                            Tu oferta fue aceptada. Ahora debes pagar la comisión para completar el trueque.
                        </p>
                    </div>
                ` : ''}

                ${offer.status === 'rejected' ? `
                    <!-- Oferta rechazada -->
                    <div class="text-center bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h5 class="font-semibold text-red-800 mb-2">Oferta Rechazada</h5>
                        <p class="text-red-700 text-sm mb-4">El destinatario ha rechazado tu oferta</p>
                        <button onclick="closeOfferModal()" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                            Cerrar
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `;

    // Animar las barras de progreso después de renderizar
    setTimeout(() => {
        const progressBars = modalContent.querySelectorAll('.bg-green-500, .bg-gray-300');
        progressBars.forEach(bar => {
            bar.style.transition = 'width 1.5s ease-in-out';
        });
    }, 100);
}

// Función para cerrar el modal
function closeOfferModal() {
    const modal = document.getElementById('offer-modal');
    animateModalClose(modal);
}

// Event listeners mejorados para el modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('offer-modal');
    const closeButton = document.getElementById('close-modal');
    
    closeButton.addEventListener('click', closeOfferModal);
    
    // Cerrar modal al hacer clic fuera del contenido
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeOfferModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeOfferModal();
        }
    });
});
</script>
@endsection