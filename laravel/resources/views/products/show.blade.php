@extends('layouts.app')

@section('title', $product->title)

@section('content')
<!-- Incluir Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="container mx-auto">
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-2xl md:text-3xl font-semibold text-black">ENCUENTRA<br><span class="text-gray-900">TU PRODUCTO <span class="font-extrabold">IDEAL</span></span></h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        <!-- Contenido principal del producto -->
        <div class="md:col-span-8 space-y-6">
            <!-- Galería + info del producto -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white shadow-lg rounded-xl p-6 border border-yellow-200">
                <!-- Galería de imágenes con Swiper -->
                <div class="space-y-4">
                    <!-- Imagen principal con zoom -->
                    <div class="swiper-container gallery-top relative rounded-lg overflow-hidden">
                        <div class="swiper-wrapper">
                            @foreach($product->images as $image)
                                <div class="swiper-slide relative">
                                    <div class="image-zoom-container relative overflow-hidden" style="height: 400px;">
                                        <img 
                                            src="{{ asset('storage/' . $image->path) }}" 
                                            alt="{{ $product->title }}" 
                                            class="zoom-image w-full h-full object-cover"
                                            data-zoom-image="{{ asset('storage/' . $image->path) }}"
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Botones de navegación -->
                        <div class="swiper-button-next bg-white/80 hover:bg-white rounded-full w-10 h-10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <div class="swiper-button-prev bg-white/80 hover:bg-white rounded-full w-10 h-10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </div>
                        
                        <!-- Contador de imágenes -->
                        @if($product->images->count() > 1)
                        <div class="absolute top-4 right-4 bg-black/50 text-white px-2 py-1 rounded-full text-sm z-10">
                            <span class="swiper-counter">1</span>/{{ $product->images->count() }}
                        </div>
                        @endif
                    </div>

                    <!-- Miniaturas -->
                    @if($product->images->count() > 1)
                    <div class="thumbs-container mt-4">
                        <div class="flex space-x-2 justify-center overflow-x-auto py-2">
                            @foreach($product->images as $index => $image)
                                <div class="thumb-item cursor-pointer border-2 border-transparent hover:border-yellow-400 rounded-lg transition-all duration-200 flex-shrink-0 {{ $index === 0 ? 'border-yellow-400' : '' }}" data-index="{{ $index }}">
                                    <img 
                                        src="{{ asset('storage/' . $image->path) }}" 
                                        alt="{{ $product->title }}"
                                        class="w-16 h-16 object-cover rounded-lg"
                                    >
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Información del producto -->
                <div class="space-y-2">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div>
                                <span class="inline-block bg-yellow-400 text-xs text-black px-3 py-1 rounded-full font-medium">{{ $product->category->name ?? 'Categoría' }}</span>
                            </div>
                        </div>
                        
                        <!-- BOTÓN COMPARTIR -->
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open"
                                class="relative z-10 text-gray-600 hover:text-gray-800 p-2 rounded-full hover:bg-gray-100 transition">
                                <i class="fa-solid fa-share-nodes text-xl"></i>
                            </button>
                            
                            <!-- Menú desplegable -->
                            <div 
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-20"
                                style="display: none;"
                            >
                                <div class="p-2">
                                    <!-- Facebook -->
                                    <a 
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('products.show', $product)) }}&quote={{ urlencode($product->title) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md mb-1"
                                    >
                                        <i class="fab fa-facebook text-blue-600 w-5 mr-2"></i>
                                        Facebook
                                    </a>
                                    
                                    <!-- WhatsApp -->
                                    <a 
                                        href="https://wa.me/?text={{ urlencode($product->title . ' ' . route('products.show', $product)) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md mb-1"
                                    >
                                        <i class="fab fa-whatsapp text-green-500 w-5 mr-2"></i>
                                        WhatsApp
                                    </a>
                                    
                                    <!-- Instagram -->
                                    <a 
                                        href="https://www.instagram.com/direct/inbox/"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 rounded-md mb-1"
                                    >
                                        <i class="fab fa-instagram text-pink-500 w-5 mr-2"></i>
                                        Instagram
                                    </a>
                                    
                                    <!-- Correo -->
                                    <a 
                                        href="mailto:?subject={{ rawurlencode('Mira este producto: ' . $product->title) }}&body={{ rawurlencode('Hola, te comparto este producto: ' . $product->title . '\n\n' . route('products.show', $product)) }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-md mb-1"
                                    >
                                        <i class="fas fa-envelope text-red-500 w-5 mr-2"></i>
                                        Correo
                                    </a>
                                    
                                    <!-- Copiar enlace -->
                                    <button 
                                        @click="
                                            navigator.clipboard.writeText('{{ route('products.show', $product) }}');
                                            open = false;
                                        "
                                        class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                                    >
                                        <i class="fas fa-link text-gray-500 w-5 mr-2"></i>
                                        Copiar enlace
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-regular text-gray-900 leading-tight">{{ $product->title }}</h1>

                    <div class="mt-4">
                        <div class="flex items-end gap-4">
                            <div>
                                <div class="text-4xl md:text-5xl font-semibold text-gray-900">${{ number_format($product->price_reference, 0, '', '.') }}</div>
                                <div class="text-xs text-gray-500">Precio Referencial</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="my-3">
                        <p class="mb-2"><strong class="text-gray-700">Intereses:</strong></p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($product->tags ? explode(',', $product->tags) : [] as $tag)
                                <span class="bg-yellow-300 text-black text-xs px-2 py-1 rounded-full font-medium break-words">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-4 flex flex-col gap-2 text-sm text-gray-600"> 
                        <div class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt w-4 h-4"></i>
                            <span><strong>Ubicación:</strong> {{ $product->location }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt w-4 h-4"></i>
                            <span><strong>Fecha Publicado:</strong> {{ $product->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <div class="text-sm text-gray-600"><strong>Condición:</strong></div>
                        <div class="font-regular text-gray-600">
                            @switch($product->condition)
                                @case('new') Nuevo @break
                                @case('used') Usado @break
                                @case('refurbished') Restaurado @break
                                @default {{ ucfirst($product->condition) }}
                            @endswitch
                        </div>
                    </div>          
                    
                    @auth
                        @if(auth()->id() !== $product->user_id)
                            @php
                                $userOffer = \App\Models\Offer::getUserOffer(auth()->id(), $product->id);
                                $canOffer = \App\Models\Offer::canUserOffer(auth()->id(), $product->id);
                            @endphp

                            @if($product->status !== 'available')
                                @switch($product->status)
                                    @case('paired')
                                        <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full">
                                            <i class="fa-solid fa-handshake mr-2"></i>
                                            Producto Permutado
                                        </button>
                                        <p class="text-sm text-gray-600 mt-2 text-center">
                                            Este producto ya ha sido permutado y no está disponible para nuevos intercambios.
                                        </p>
                                        @break
                                    
                                    @case('sold')
                                        <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full">
                                            <i class="fa-solid fa-tag mr-2"></i>
                                            Producto Vendido
                                        </button>
                                        <p class="text-sm text-gray-600 mt-2 text-center">
                                            Este producto ha sido vendido y no está disponible para intercambios.
                                        </p>
                                        @break
                                    
                                    @case('inactive')
                                        <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full">
                                            <i class="fa-solid fa-pause mr-2"></i>
                                            Producto Inactivo
                                        </button>
                                        <p class="text-sm text-gray-600 mt-2 text-center">
                                            Este producto está temporalmente inactivo.
                                        </p>
                                        @break
                                    
                                    @default
                                        <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full">
                                            <i class="fa-solid fa-ban mr-2"></i>
                                            No Disponible
                                        </button>
                                        <p class="text-sm text-gray-600 mt-2 text-center">
                                            Este producto no está disponible para intercambios.
                                        </p>
                                @endswitch
                            @elseif($userOffer)
                                @switch($userOffer->status)
                                    @case('pending')
                                        <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full">
                                            <i class="fa-solid fa-clock mr-2"></i>
                                            Oferta Pendiente
                                        </button>
                                        <p class="text-sm text-gray-600 mt-2 text-center">
                                            Ya tienes una oferta en espera. Espera la respuesta del vendedor.
                                        </p>
                                        @break
                                    
                                    @case('accepted')
                                        <button disabled class="btn-green opacity-70 cursor-not-allowed w-full">
                                            <i class="fa-solid fa-check mr-2"></i>
                                            Oferta Aceptada
                                        </button>
                                        <p class="text-sm text-green-600 mt-2 text-center">
                                            <i class="fa-solid fa-message mr-1"></i>
                                            ¡Tu oferta fue aceptada! 
                                            @if($userOffer->chat)
                                                <a href="{{ route('chats.show', $userOffer->chat->id) }}" class="underline font-medium">
                                                    Coordina el intercambio en el chat
                                                </a>
                                            @else
                                                <span>Se ha creado un chat para coordinar el intercambio.</span>
                                            @endif
                                        </p>
                                        @break
                                    
                                    @case('waiting_payment')
                                        <a href="{{ route('checkout.commission-offered', $userOffer) }}" class="w-full block">
                                            <button class="btn-orange w-full">
                                                <i class="fa-solid fa-credit-card mr-2"></i>
                                                Pagar Comisión
                                            </button>
                                        </a>
                                        <p class="text-sm text-orange-600 mt-2 text-center">
                                            Completa el pago de la comisión para finalizar el trueque.
                                        </p>
                                        @break
                                    
                                    @case('rejected')
                                        <a href="{{ route('offers.create', $product) }}" 
                                           class="btn-orange w-full text-center" 
                                           title="Tu oferta anterior fue rechazada. Puedes hacer una nueva oferta">
                                            <i class="fa-solid fa-rotate-right mr-2"></i>
                                            Ofertar Nuevamente
                                        </a>
                                        <p class="text-sm text-gray-600 mt-2 text-center">
                                            Tu oferta anterior fue rechazada. Puedes intentar con una nueva oferta.
                                        </p>
                                        @break
                                @endswitch
                            @elseif($canOffer)
                                <a href="{{ route('offers.create', $product) }}"
                                   class="btn-orange text-center">
                                    <i class="fa-solid fa-handshake mr-2"></i>
                                    OFERTAR
                                </a>
                            @else
                                <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full">
                                    <i class="fa-solid fa-ban mr-2"></i>
                                    No puedes ofertar este producto
                                </button>
                            @endif
                        @else
                            @php
                                $activeOffers = \App\Models\Offer::where('product_requested_id', $product->id)
                                    ->whereIn('status', ['pending', 'accepted', 'waiting_payment'])
                                    ->count();
                            @endphp
                            
                            @if($product->status !== 'available')
                                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <p class="text-yellow-700 font-medium">
                                        <i class="fa-solid fa-info-circle mr-2"></i>
                                        Estado del Producto: 
                                        @switch($product->status)
                                            @case('paired') Permutado @break
                                            @case('sold') Vendido @break
                                            @case('inactive') Inactivo @break
                                            @default No Disponible
                                        @endswitch
                                    </p>
                                    <p class="text-sm text-yellow-600 mt-1">
                                        Este producto no está disponible para nuevos intercambios.
                                    </p>
                                </div>
                            @elseif($activeOffers > 0)
                                <p class="text-green-600 font-medium text-center">
                                    <i class="fa-solid fa-bell mr-2"></i>
                                    Tienes {{ $activeOffers }} oferta(s) activa(s)
                                </p>
                                <a href="{{ route('dashboard.received-offers') }}" class="text-blue-600 underline text-sm text-center block mt-2">
                                    Gestionar ofertas recibidas
                                </a>
                            @else
                                <p class="text-gray-500 font-medium text-left">
                                    <i class="fa-solid fa-info-circle mr-2"></i>
                                    Eres el dueño de este producto
                                </p>
                            @endif
                        @endif
                    @else
                        @if($product->status === 'available')
                            <p class="text-gray-500 text-left">
                                <i class="fa-solid fa-sign-in-alt mr-2"></i>
                                Debes <a href="{{ route('login') }}" class="text-blue-600 underline">iniciar sesión</a> para ofertar
                            </p>
                        @else
                            <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full">
                                <i class="fa-solid fa-ban mr-2"></i>
                                Producto No Disponible
                            </button>
                            <p class="text-sm text-gray-600 mt-2 text-center">
                                Este producto no está disponible para intercambios.
                            </p>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Descripción del producto -->
            <div class="questions-container max-w-full mx-auto bg-white rounded-lg shadow overflow-hidden border border-yellow-200">
                <div class="bg-yellow-300 px-6 py-3">
                    <div class="max-w-7xl mx-auto flex justify-start">
                        <h2 class="text-xl font-semibold text-black text-left">Descripción</h2>
                    </div>
                </div>
                <div class="px-6 py-6 max-w-7xl mx-auto">
                    <p class="text-gray-700 leading-relaxed break-words">
                        {!! nl2br(e(trim($product->description))) !!}
                    </p>
                </div>
            </div>

            <!-- Sección de preguntas -->
            <div class="">
                @livewire('questions-component', ['product' => $product])
            </div>
        </div>

        <!-- Sidebar del perfil del usuario -->
        <aside class="md:col-span-4 self-start">
            <div class="w-full  bg-white rounded-lg shadow-md overflow-hidden text-center">
                <!-- Header -->
                <div class="bg-yellow-300 flex justify-between items-center px-4 py-2">
                    <!-- Rating -->
                    <div class="flex items-center bg-yellow-500 text-gray-700 text-sm font-semibold px-2 py-1 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.449a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.54 1.118l-3.37-2.449a1 1 0 00-1.176 0l-3.37 2.449c-.784.57-1.838-.197-1.539-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.07 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.279-3.955z"/>
                        </svg>
                        @if(!$product->user->rating || $product->user->rating == 0)
                            Nuevo
                        @else
                            {{ number_format($product->user->rating, 1) }}
                        @endif
                    </div>

                    <!-- Icono para ir al perfil -->
                    <a href="{{ route('user.profile', $product->user->id) }}" class="text-gray-700 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <!-- Avatar -->
                <div class="flex justify-center -mt-10">
                    <a href="{{ route('user.profile', $product->user->id) }}">
                        <img src="{{ $product->user->profile_photo_url }}"
                             alt="{{ $product->user->alias }}"
                             class="w-20 h-20 rounded-full border-4 border-white shadow-md object-cover">
                    </a>
                </div>

                <!-- Nombre -->
                <h3 class="text-lg font-semibold text-gray-900 mt-2">{{ $product->user->name }}</h3>
                <p class="text-sm text-gray-500">Miembro desde {{ $product->user->created_at->format('d/m/Y') }}</p>

                <!-- Estadísticas -->
                <div class="grid grid-cols-3 mt-4 border-t border-gray-100">
                    <div class="py-3">
                        <p class="text-lg font-bold text-gray-900">{{ $product->user->products_count }}</p>
                        <p class="text-xs text-gray-600">Productos</p>
                    </div>
                    <div class="py-3 border-x border-gray-100">
                        <p class="text-lg font-bold text-gray-900">{{ $product->user->completed_permutas_count ?? 0 }}</p>
                        <p class="text-xs text-gray-600">Permutas</p>
                    </div>
                    <div class="py-3">
                        <p class="text-lg font-bold text-gray-900">{{ $product->user->reviews_count ?? 0 }}</p>
                        <p class="text-xs text-gray-600">Reseñas</p>
                    </div>
                </div>
            </div>

            <!-- Productos relacionados -->
            @if($relatedProducts->count() > 0)
            <div class="mt-6">
                <h3 class="font-bold text-xl mb-3 text-gray-600  pb-2">Productos relacionados</h3>
      <div class="w-full h-[3px] flex mb-8">
    <div class="w-full bg-gray-300 rounded-full"></div>
</div>
                <div class="space-y-4">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="block relative w-2/5 flex-shrink-0">
                                @if($relatedProduct->images->count() > 0)
                                    <img 
                                        src="{{ asset('storage/' . $relatedProduct->images->first()->path) }}" 
                                        alt="{{ $relatedProduct->title }}" 
                                        class="absolute inset-0 w-full h-full object-cover object-center" 
                                    >
                                @else
                                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">Sin imagen</span>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="p-3 w-3/5 flex flex-col justify-between">
                                <div>
                                    <a href="{{ route('products.show', $relatedProduct) }}" class="block">
                                        <h5 class="font-semibold text-gray-800 text-sm hover:text-blue-600 transition-colors line-clamp-2 mb-1">
                                            {{ Str::limit($relatedProduct->title, 40) }}
                                        </h5>
                                    </a>
                                    
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ $relatedProduct->category->name }}
                                        </span>
                                        <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                            @switch($relatedProduct->condition)
                                                @case('new') Nuevo @break
                                                @case('used') Usado @break
                                                @case('refurbished') Restaurado @break
                                            @endswitch
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <span class="text-sm font-semibold text-blue-600">
                                        ${{ number_format($relatedProduct->price_reference, 0, '', '.') }}
                                        </span>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500 space-y-1">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>{{ Str::limit($relatedProduct->location, 20) }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span>por {{ Str::limit($relatedProduct->user->name, 15) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <a 
                                    href="{{ route('products.show', $relatedProduct) }}" 
                                    class="block mt-2 text-center bg-yellow-400 text-white text-xs font-medium py-2 rounded-lg transition-colors"
                                >
                                    VER PRODUCTO 
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mt-6 p-4 bg-gray-50 rounded-lg text-center">
                <p class="text-gray-600 text-sm">No hay productos similares disponibles en este momento.</p>
            </div>
            @endif
        </aside>
    </div>
    
    <!-- Componente del modal de intercambio -->
    @livewire('exchange-modal')
</div>

<!-- Incluir Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
.swiper-container {
    width: 100%;
    border-radius: 0.5rem;
}

.gallery-top {
    height: 400px;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

/* Estilos para el zoom - OPTIMIZADO PARA VELOCIDAD */
.image-zoom-container {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.zoom-image {
    cursor: crosshair;
    /* QUITAMOS LA TRANSICIÓN para máxima velocidad */
    transition: none;
    transform-origin: 0 0;
    will-change: transform; /* Optimización para el navegador */
}

/* Miniaturas fijas */
.thumbs-container {
    width: 100%;
    overflow-x: auto;
}

.thumbs-container::-webkit-scrollbar {
    height: 4px;
}

.thumbs-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.thumbs-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.thumbs-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.thumb-item {
    flex-shrink: 0;
    width: 64px;
    height: 64px;
}

.thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumb-item.active {
    border-color: #fbbf24 !important;
}

.swiper-button-next,
.swiper-button-prev {
    background: rgba(255, 255, 255, 0.8);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background: white;
}

.swiper-button-next:after,
.swiper-button-prev:after {
    display: none;
}

.swiper-pagination-bullet {
    background: white;
    opacity: 0.6;
}

.swiper-pagination-bullet-active {
    background: white;
    opacity: 1;
}

.swiper-counter {
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Swiper principal SIN loop para evitar problemas
    const galleryTop = new Swiper('.gallery-top', {
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        loop: false, // Desactivar loop para evitar problemas
        speed: 300,
    });

    // Actualizar contador de imágenes
    const counterElement = document.querySelector('.swiper-counter');
    if (counterElement && {{ $product->images->count() }} > 1) {
        galleryTop.on('slideChange', function () {
            counterElement.textContent = galleryTop.activeIndex + 1;
            updateActiveThumb(galleryTop.activeIndex);
        });
    }

    // Funcionalidad para miniaturas
    function updateActiveThumb(activeIndex) {
        document.querySelectorAll('.thumb-item').forEach((thumb, index) => {
            if (index === activeIndex) {
                thumb.classList.add('border-yellow-400');
                thumb.classList.remove('border-transparent');
            } else {
                thumb.classList.remove('border-yellow-400');
                thumb.classList.add('border-transparent');
            }
        });
    }

    // Event listeners para miniaturas
    document.querySelectorAll('.thumb-item').forEach((thumb, index) => {
        thumb.addEventListener('click', function() {
            galleryTop.slideTo(index);
            updateActiveThumb(index);
        });
    });

    // Zoom tipo AliExpress - OPTIMIZADO PARA VELOCIDAD
    function initZoom() {
        const zoomContainers = document.querySelectorAll('.image-zoom-container');
        
        zoomContainers.forEach(container => {
            const image = container.querySelector('.zoom-image');
            let isZoomed = false;
            const zoomLevel = 2;
            
            // Usar requestAnimationFrame para mejor rendimiento
            let rafId = null;

            function handleMouseMove(e) {
                if (!isZoomed) return;
                
                // Cancelar el frame anterior si existe
                if (rafId) {
                    cancelAnimationFrame(rafId);
                }
                
                // Usar requestAnimationFrame para mejor performance
                rafId = requestAnimationFrame(() => {
                    const rect = container.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    // Calcular la posición del zoom basado en la posición del mouse
                    const percentX = (x / rect.width) * 100;
                    const percentY = (y / rect.height) * 100;

                    // Calcular el desplazamiento para que la imagen se mueva con el mouse
                    const moveX = (percentX - 50) * (zoomLevel - 1);
                    const moveY = (percentY - 50) * (zoomLevel - 1);

                    // Aplicar transform SIN transición para máxima velocidad
                    image.style.transform = `scale(${zoomLevel}) translate(${-moveX}%, ${-moveY}%)`;
                });
            }

            function handleMouseEnter() {
                isZoomed = true;
                image.style.transform = `scale(${zoomLevel})`;
                image.style.cursor = 'zoom-out';
            }

            function handleMouseLeave() {
                isZoomed = false;
                // Cancelar cualquier animación pendiente
                if (rafId) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                }
                image.style.transform = 'scale(1)';
                image.style.cursor = 'crosshair';
            }

            // Usar passive events para mejor rendimiento
            const passiveOptions = { passive: true };
            
            // Event listeners optimizados
            container.addEventListener('mouseenter', handleMouseEnter, passiveOptions);
            container.addEventListener('mouseleave', handleMouseLeave, passiveOptions);
            container.addEventListener('mousemove', handleMouseMove, passiveOptions);

            // También permitir zoom con click
            container.addEventListener('click', function(e) {
                if (!isZoomed) {
                    handleMouseEnter();
                    handleMouseMove(e);
                } else {
                    handleMouseLeave();
                }
            }, passiveOptions);
        });
    }

    // Inicializar zoom cuando cambie la slide
    galleryTop.on('slideChange', function() {
        // Pequeño delay para asegurar que el DOM se ha actualizado
        setTimeout(initZoom, 10);
    });

    // Inicializar zoom al cargar
    setTimeout(initZoom, 50);
});
</script>
@endsection