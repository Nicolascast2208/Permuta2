{{-- resources/views/home.blade.php --}}
@extends('layouts.app') 

@section('title', 'Inicio')

@section('content')
<div class="max-w-7xl mx-auto py-2">
{{-- HERO BANNER --}}
<section class="mb-6 md:mb-28 relative">
    {{-- Swiper para desktop --}}
    <div class="absolute inset-0 z-10 hidden md:block">
        <div class="swiper mySwiperHome h-full rounded-xl">
            <div class="swiper-wrapper">
                <div class="swiper-slide bg-cover bg-center" style="background-image: url('{{ asset('storage/images/fondo-2.jpg') }}');"></div>
                <div class="swiper-slide bg-cover bg-center" style="background-image: url('{{ asset('storage/images/fondo-1.jpg') }}');"></div>
                <div class="swiper-slide bg-cover bg-center" style="background-image: url('{{ asset('storage/images/fondo-3.jpg') }}');"></div>
            </div>
        </div>
        <div class="absolute inset-0 bg-white/70"></div> {{-- filtro semitransparente --}}
    </div>

    {{-- Swiper para móvil --}}
    <div class="absolute inset-0 z-10 md:hidden">
        <div class="swiper mySwiperMobile h-full rounded-xl">
            <div class="swiper-wrapper">
                  <div class="swiper-slide bg-cover bg-center" style="background-image: url('{{ asset('storage/images/banner-movil1.png') }}');"></div>
                             <div class="swiper-slide bg-cover bg-center" style="background-image: url('{{ asset('storage/images/banner-movil2.png') }}');"></div>
                                             <div class="swiper-slide bg-cover bg-center" style="background-image: url('{{ asset('storage/images/banner-movil3.png') }}');"></div>
            </div>
            <!-- Paginación para móvil -->
            <div class="swiper-pagination mobile-pagination"></div>
        </div>
        <div class="absolute inset-0 bg-white/40"></div> {{-- filtro más claro para móvil --}}
    </div>

    <!-- Flechas personalizadas SOLO para desktop -->
    <button class="hidden md:flex custom-prev absolute left-6 top-1/2 -translate-y-1/2 z-40 pointer-events-auto group">
        <div class="relative flex items-center justify-center">
            <!-- Círculo blanco grande -->
            <div class="absolute w-16 h-16 bg-white rounded-full "></div>
            <!-- Círculo gris pequeño -->
            <div class="relative w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center shadow-md group-hover:bg-gray-300 transition">
                <i class="fas fa-chevron-left text-orange-500 text-md"></i>
            </div>
        </div>
    </button>
    <button class="hidden md:flex custom-next absolute right-6 top-1/2 -translate-y-1/2 z-40 pointer-events-auto group">
        <div class="relative flex items-center justify-center">
            <!-- Círculo blanco grande -->
            <div class="absolute w-16 h-16 bg-white rounded-full "></div>
            <!-- Círculo gris pequeño -->
            <div class="relative w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center shadow-md group-hover:bg-gray-300 transition">
                <i class="fas fa-chevron-right text-orange-500 text-md"></i>
            </div>
        </div>
    </button>

    <div class="relative rounded-xl  pb-20 z-10 hero-fixed-height">
        {{-- Quick cards --}}
        <div class="absolute left-1/2 bottom-0 translate-y-1/2 -translate-x-1/2 w-11/12 md:w-4/5 hidden md:block">
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                {{-- Card 1 --}}
                <a href="{{ route('products.createx') }}">
                    <div class="rounded-xl shadow p-4 text-center bg-gradient-to-b from-white to-[#E9E9E9] transition duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="font-semibold fondo-amarillo rounded-xl mb-3 py-2 text-sm">PUBLICAR</div>
                        <div class="mb-2 texto-amarillo">
                            <div class="mb-3 flex items-center justify-center">
                                <img src="{{ asset('storage/images/icono-1-home.png') }}" class="h-14">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Publica e intercambia</div>
                    </div>
                </a>

                {{-- Card 2 --}}
                <a href="{{ route('dashboard') }}">
                    <div class="rounded-xl shadow p-4 text-center bg-gradient-to-b from-white to-[#E9E9E9] transition duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="font-semibold fondo-amarillo rounded-xl mb-3 py-2 text-sm">MI PERFIL</div>
                        <div class="mb-2 texto-amarillo">
                            <div class="mb-3 flex items-center justify-center">
                                <img src="{{ asset('storage/images/icono-2-home.png') }}" class="h-14">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Perfil de usuario</div>
                    </div>
                </a>

                {{-- Card 3 --}}
                <a href="{{ route('categories.index') }}">
                    <div class="rounded-xl shadow p-4 text-center bg-gradient-to-b from-white to-[#E9E9E9] transition duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="font-semibold fondo-amarillo rounded-xl mb-3 py-2 text-sm">CATEGORÍAS</div>
                        <div class="mb-2 texto-amarillo">
                            <div class="mb-3 flex items-center justify-center">
                                <img src="{{ asset('storage/images/icono-3-home.png') }}" class="h-14">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Productos y servicios</div>
                    </div>
                </a>

                {{-- Card 4 --}}
                <a href="{{ route('como.permutar') }}">
                    <div class="rounded-xl shadow p-4 text-center bg-gradient-to-b from-white to-[#E9E9E9] transition duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="font-semibold fondo-amarillo rounded-xl mb-3 py-2 text-sm">¿CÓMO PERMUTAR?</div>
                        <div class="mb-2 texto-amarillo">
                            <div class="mb-3 flex items-center justify-center">
                                <img src="{{ asset('storage/images/icono-4-home.png') }}" class="h-14">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Intercambio responsable</div>
                    </div>
                </a>

                {{-- Card 5 --}}
                <a href="{{ route('users.index') }}">
                    <div class="rounded-xl shadow p-4 text-center bg-gradient-to-b from-white to-[#E9E9E9] transition duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="font-semibold fondo-amarillo rounded-xl mb-3 py-2 text-sm">PERMUTADORES</div>
                        <div class="mb-2 texto-amarillo">
                            <div class="mb-3 flex items-center justify-center">
                                <img src="{{ asset('storage/images/icono-5-home.png') }}" class="h-14">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Comunidad permuta2</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

 {{-- DESTACADOS + NOVEDADES --}}
    <section class="max-w-6xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-500">Destacados</h2>
           
        </div>
<div class="w-full h-[3px] flex mb-8">
    <div class="w-full bg-gray-300 rounded-full"></div>
</div>

<div class="relative">
    <div class="swiper destacadosSwiper">
        <div class="swiper-wrapper">
            @foreach($featuredProducts as $product)
            <div class="swiper-slide">
                <a href="{{ route('products.show', $product->id) }}" 
                   class="bg-white rounded-2xl shadow hover:shadow-lg transition p-4 flex flex-col h-full border border-transparent hover:border-blue-400 block">
                    
                    <!-- Imagen -->
                    <img src="{{ $product->first_image_url }}" 
                         class="rounded-xl w-full h-48 object-cover mb-3" 
                         alt="{{ $product->title }}">

                    <!-- Título -->
                    <h3 class="text-base font-semibold text-gray-800 line-clamp-1 mb-1">
                        {{ $product->title }}
                    </h3>

                    <!-- Precio -->
                    <span class="text-lg font-bold text-gray-900 mb-2">
                        ${{ number_format($product->price_reference, 0, ',', '.') }}
                    </span>

                    <hr class="mb-2">

                    <!-- Categoría y ubicación -->
                    <div class="flex items-center justify-between flex-wrap gap-1">
                        <span class="bg-yellow-300 text-black text-xs px-2 py-1 rounded-full font-medium">
                            {{ $product->category->name ?? 'Sin categoría' }}
                        </span>

                        <span class="text-xs text-gray-600 truncate max-w-[60%]">
                            <i class="fas fa-map-marker-alt text-orange-500 mr-1"></i>
                            {{ $product->location }}
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <!-- Paginación con puntos -->
        <div class="swiper-pagination destacados-pagination mt-6"></div>
    </div>
</div>
    </section>


    {{-- TOP CATEGORÍAS --}}
    <section class="bg-white py-12">
        <div class="max-w-6xl mx-auto px-4">
           <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-500">Permuta en <span class="text-blue-400">Top Categorías</span></h2>
            <a href="{{ route('categories.index') }}" class="text-gray-500 hover:underline font-semibold">Ver todas </a>
        </div>
<div class="w-full h-[3px] flex mb-8">
    <div class="w-full bg-gray-300 rounded-full"></div>
</div>
<div class="grid grid-cols-2 md:grid-cols-6 gap-4">
    @foreach($randomCategories as $category)
        <a href="{{ route('categories.show', $category->slug) }}" 
           class="flex flex-col items-center text-center p-2 transition hover:scale-105">

            <div class="w-20 h-20 flex items-center justify-center rounded-full border border-gray-100 bg-gray-100 shadow-sm hover:border-blue-500 transition">
                <img src="{{ asset('storage/' . $category->image) }}" 
                     alt="{{ $category->name }}" 
                     class="w-10 h-10 object-contain svg-yellow ">
            </div>

            <span class="mt-2 text-gray-700 text-sm font-medium">{{ $category->name }}</span>
        </a>
    @endforeach
</div>
        </div>
    </section>

    {{-- CAROUSEL RECOMENDADOS (scroll horizontal) --}}
    <section class="max-w-6xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-500">Para Ti</h2>
           
        </div>
<div class="w-full h-[3px] flex mb-8">
    <div class="w-full bg-gray-300 rounded-full"></div>
</div>

<div class="relative">
    <div class="swiper paraTiSwiper">
        <div class="swiper-wrapper">
            @foreach($recommendedProducts as $product)
            <div class="swiper-slide">
                <a href="{{ route('products.show', $product->id) }}" 
                   class="bg-white rounded-2xl shadow hover:shadow-lg transition p-4 flex flex-col h-full border border-transparent hover:border-blue-400 block">
                    
                    <!-- Imagen -->
                    <img src="{{ $product->first_image_url }}" 
                         class="rounded-xl w-full h-48 object-cover mb-3" 
                         alt="{{ $product->title }}">

                    <!-- Título -->
                    <h3 class="text-base font-semibold text-gray-800 line-clamp-1 mb-1">
                        {{ $product->title }}
                    </h3>

                    <!-- Precio -->
                    <span class="text-lg font-bold text-gray-900 mb-2">
                        ${{ number_format($product->price_reference, 0, ',', '.') }}
                    </span>

                    <hr class="mb-2">

                    <!-- Categoría y ubicación -->
                    <div class="flex items-center justify-between flex-wrap gap-1">
                        <span class="bg-yellow-300 text-black text-xs px-2 py-1 rounded-full font-medium">
                            {{ $product->category->name ?? 'Sin categoría' }}
                        </span>

                        <span class="text-xs text-gray-600 truncate max-w-[60%]">
                            <i class="fas fa-map-marker-alt text-orange-500 mr-1"></i>
                            {{ $product->location }}
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <!-- Paginación con puntos -->
        <div class="swiper-pagination paraTi-pagination mt-6"></div>
    </div>
</div>
    </section>


    {{-- PROCESO PARA PERMUTAR --}}
    <section class="mb-8 text-center">
               <div class="max-w-6xl mx-auto px-4">
           <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-500">Cómo Permutar</h2>
        
        </div>
<div class="w-full h-[3px] flex mb-8">
    <div class="w-full bg-gray-300 rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-10">
  <!-- Carta 1 -->
  <div class="relative bg-gray-100 rounded-xl px-10 py-12 w-full md:w-[420px] shadow-sm">
    <div class="absolute top-4 left-4 bg-yellow-400 text-white font-bold rounded-full w-14 h-14 flex items-center justify-center text-2xl shadow">
      1
    </div>

    <div class="flex justify-center mb-3">
      <img src="{{ asset('storage/images/icono-registra.svg') }}" alt="registra" class="w-auto h-12">
    </div>

    <div class="font-semibold buscador rounded-xl mb-3 py-1 px-4 text-md inline-block text-white bg-blue-500">
      Regístrate y publica
    </div>

    <p class="text-sm text-gray-600 mt-2">
      Crea tu cuenta y publica<br>tus artículos con fotos y descripción.
    </p>
  </div>

  <!-- Carta 2 -->
  <div class="relative bg-gray-100 rounded-xl px-10 py-12 w-full md:w-[420px] shadow-sm">
    <div class="absolute top-4 left-4 bg-yellow-400 text-white font-bold rounded-full w-14 h-14 flex items-center justify-center text-2xl shadow">
      2
    </div>

    <div class="flex justify-center mb-3">
      <img src="{{ asset('storage/images/icono-busca.svg') }}" alt="buscar" class="w-auto h-12">
    </div>

    <div class="font-semibold buscador rounded-xl mb-3 py-1 px-4 text-md inline-block text-white bg-blue-500">
      Busca y pacta
    </div>

    <p class="text-sm text-gray-600 mt-2">
      Filtra por categoría o distancia<br>y encuentra el intercambio ideal.
    </p>
  </div>

  <!-- Carta 3 -->
  <div class="relative bg-gray-100 rounded-xl px-10 py-12 w-full md:w-[420px] shadow-sm">
    <div class="absolute top-4 left-4 bg-yellow-400 text-white font-bold rounded-full w-14 h-14 flex items-center justify-center text-2xl shadow">
      3
    </div>

    <div class="flex justify-center mb-3">
      <img src="{{ asset('storage/images/icono-intercambia.svg') }}" alt="intercambia" class="w-auto h-12">
    </div>

    <div class="font-semibold buscador rounded-xl mb-3 py-1 px-4 text-md inline-block text-white bg-blue-500">
      Intercambia
    </div>

    <p class="text-sm text-gray-600 mt-2">
      Coordina la entrega y<br>califica al permutador al finalizar.
    </p>
  </div>
</div>

    </section>

 {{-- UTILIZA TUS TARJETAS AQUÍ --}}
<section class="max-w-6xl mx-auto px-6 py-8">
    <div class="bg-gray-100 rounded-2xl p-12 md:p-12 shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex-1">
            <h2 class="text-2xl md:text-4xl font-bold text-gray-800">UTILIZA TUS TARJETAS <span class="italic">AQUÍ</span></h2>
           
        </div>

   
        <div class="flex-shrink-0">
    
            <img src="{{ asset('storage/images/logo-webpay.png') }}" alt="WebPay" class="h-12 md:h-16 object-contain">
        </div>
    </div>
</section>

{{-- TOP ELECTRÓNICA (3 cartas horizontales) --}}
<section class="max-w-6xl mx-auto px-6 py-8">
    <div class="mb-10">
     <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-500">Top Electrónica</h2>
        
        </div>
<div class="w-full h-[3px] flex mb-8">
    <div class="w-full bg-gray-300 rounded-full"></div>
</div>

   

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<!-- CARD 1 -->
<a href="{{ route('categories.show', ['slug' => 'celulares-y-telefonos']) }}" class="block">
    <div class="relative rounded-2xl p-6 text-white flex flex-col justify-between shadow-md hover:shadow-lg transition overflow-hidden h-52 md:h-56"
         style="background-image: url('{{ asset('storage/images/top-celular-fondo.png') }}'); background-size: cover; background-position: center;">

        <div class="z-10 relative h-full">
            <span class="text-xs font-semibold uppercase bg-gray-700/70 px-3 py-1 rounded-xl w-max">Celulares</span>
            <h3 class="absolute bottom-4 left-4 text-lg font-semibold leading-snug max-w-[65%] text-black">
                Encuentra tu<br> próximo equipo
            </h3>
        </div>

        <img src="{{ asset('storage/images/top-celular.png') }}"
             alt="Celular"
             class="absolute right-4 top-2/3 -translate-y-1/2 w-32 md:w-36 h-auto object-contain z-0">
    </div>
</a>

<!-- CARD 2 -->
<a href="{{ route('categories.show', ['slug' => 'computacion']) }}" class="block">
    <div class="relative rounded-2xl p-6 text-gray-900 flex flex-col justify-between shadow-md hover:shadow-lg transition overflow-hidden bg-yellow-100 h-52 md:h-56"
         style="background-image: url('{{ asset('storage/images/top-computadores-fondo.png') }}'); background-size: cover; background-position: center;">

        <div class="z-10 relative h-full">
            <span class="text-xs font-semibold uppercase bg-yellow-300 px-3 py-1 rounded-xl w-max">Computadores</span>
            <h3 class="absolute bottom-4 left-4 text-lg font-semibold leading-snug max-w-[65%]">
                Tu mejor<br> opción
            </h3>
        </div>

        <img src="{{ asset('storage/images/top-computadores-2.png') }}"
             alt="Computador"
             class="absolute right-4 top-1/2 -translate-y-1/2 w-32 md:w-36 h-auto object-contain z-0">
    </div>
</a>


<!-- CARD 3 -->
<a href="{{ route('categories.show', ['slug' => 'videojuegos']) }}" class="block">
    <div class="relative rounded-2xl p-6 text-gray-900 flex flex-col justify-between shadow-md hover:shadow-lg transition overflow-hidden bg-orange-100 h-52 md:h-56"
         style="background-image: url('{{ asset('storage/images/top-videojuegos-fondo.png') }}'); background-size: cover; background-position: center;">

        <div class="z-10 relative h-full">
            <span class="text-xs font-semibold uppercase bg-orange-300 px-3 py-1 rounded-xl w-max">Videojuegos</span>
            <h3 class="absolute bottom-4 left-4 text-lg font-semibold leading-snug max-w-[65%]">
                No pagues<br> de más
            </h3>
        </div>

        <img src="{{ asset('storage/images/top-videojuegos.png') }}"
             alt="Videojuegos"
             class="absolute right-4 top-1/2 -translate-y-1/2 w-38 md:w-36 h-auto object-contain z-0">
    </div>
</a>
</section>

{{-- CATEGORÍAS GRANDES CON SLIDER --}}
<section class="mb-8">
    <div class="categories-swiper swiper-container relative fondo-amarillo rounded-xl">
        <div class="swiper-wrapper">
            @foreach($mainCategories->chunk(8) as $chunk)
            <div class="swiper-slide">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($chunk as $category)
                    <div class="bg-white rounded-lg shadow-lg p-0 flex h-full">
                        <!-- Columna izquierda: texto -->
                        <div class="flex-1 p-4">
                            <h5 class="font-semibold mb-2 text-gray-800">{{ $category->name }}</h5>
                            <ul class="text-xs text-gray-600 space-y-1">
                                @foreach($category->children->take(3) as $child)
                                    <li class="truncate">{{ $child->name }}</li>
                                @endforeach
                                @if($category->children->count() > 3)
                                    <li class="text-yellow-500 font-medium">+{{ $category->children->count() - 3 }} más</li>
                                @endif
                            </ul>
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="inline-block mt-3 text-xs text-yellow-500 font-medium hover:text-yellow-600">
                                Ver todos →
                            </a>
                        </div>

                        <!-- Columna derecha: imagen -->
                        <div class="w-28 bg-gray-100 flex items-center justify-center rounded-r-lg">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="max-h-12 object-contain svg-yellow">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="h-8 w-8 text-gray-400" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="categories-swiper-pagination swiper-pagination mt-4"></div>

        <!-- Botones centrados -->
        <div class="flex justify-center mt-4 space-x-4">
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>


</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
    .svg-yellow {
filter: invert(57%) sepia(14%) saturate(4947%) hue-rotate(8deg) brightness(103%) contrast(104%);
}
   
    
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    
    /* Estilos específicos para el carrusel de categorías */
    .categories-swiper {
        padding: 20px 10px 10px;
        overflow: hidden;
        width: 100%;
    }
    
    .categories-swiper .swiper-wrapper {
        padding-bottom: 10px;
    }
    
    .categories-swiper .swiper-slide {
        height: auto;
    }
    
    .categories-swiper .swiper-pagination-bullet-active {
        background-color: #F59E0B;
    }
    
    .categories-swiper .swiper-button-next, 
    .categories-swiper .swiper-button-prev {
        color: #F59E0B;
        background-color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        top: 10%;
        
    }
    
    .categories-swiper .swiper-button-next:after, 
    .categories-swiper .swiper-button-prev:after {
        font-size: 20px;
        font-weight: bold;
    }
    
    /* Estilos para los swipers de Destacados y Para Ti */
    .destacadosSwiper,
    .paraTiSwiper {
        padding: 10px 10px 40px;
    }
    
    .destacadosSwiper .swiper-slide,
    .paraTiSwiper .swiper-slide {
        height: auto;
    }
    
    .destacados-pagination,
    .paraTi-pagination {
        position: relative;
        bottom: 0;
    }
    
    .destacados-pagination .swiper-pagination-bullet,
    .paraTi-pagination .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: #cbd5e0;
        opacity: 0.7;
    }
    
    .destacados-pagination .swiper-pagination-bullet-active,
    .paraTi-pagination .swiper-pagination-bullet-active {
        background: #60a5fa;
        opacity: 1;
    }

    /* Estilos específicos para el slider móvil */
    .mySwiperMobile {
          height: 40vh !important;
    min-height: 350px;
    }
    
    .mySwiperMobile .swiper-slide {
        background-size: cover !important;
        background-position: center center !important;
    }
    
    .mobile-pagination {
        bottom: 20px !important;
    }
    
    .mobile-pagination .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: white;
        opacity: 0.7;
    }
    
    .mobile-pagination .swiper-pagination-bullet-active {
        background: #F59E0B;
        opacity: 1;
    }
    
    /* Asegurar que el slider de desktop se oculte en móvil y viceversa */
    .mySwiperHome {
        display: block;
    }
    
    .mySwiperMobile {
        display: none;
    }
    
    @media (max-width: 768px) {
        .mySwiperHome {
            display: none;
        }
        
        .mySwiperMobile {
            display: block;
        }
        
        .hero-fixed-height {
            min-height: 60vh;
        }
    }
    
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Inicializar Swiper para el carrusel de categorías
        var categoriesSwiper = new Swiper('.categories-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: '.categories-swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 1,
                    spaceBetween: 25,
                }
            }
        });
        
        // Inicializar Swiper para Destacados
        var destacadosSwiper = new Swiper('.destacadosSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
              loop: true,
                      autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
            pagination: {
                el: '.destacados-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 25,
                }
            }
        });
        
        // Inicializar Swiper para Para Ti
        var paraTiSwiper = new Swiper('.paraTiSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
            pagination: {
                el: '.paraTi-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 25,
                }
            }
        });

        // Swiper para móvil
        var mobileSwiper = new Swiper(".mySwiperMobile", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".mobile-pagination",
                clickable: true,
            },
            effect: "fade",
            speed: 1000,
        });

        // Swiper para desktop (original)
        var desktopSwiper = new Swiper(".mySwiperHome", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".custom-next",
                prevEl: ".custom-prev",
            },
            effect: "fade",
            speed: 1000,
        });

    });
</script>
@endpush
<style>
.hero-fixed-height {
  height: auto;            /* por defecto deja que el contenido ajuste la altura */
  min-height: 0;
}

/* A partir de 768px (md) fijamos 300px */
@media (min-width: 768px) {
  .hero-fixed-height {
    height: 300px !important;   /* !important en caso de conflictos con otras reglas */
    min-height: 300px;
  }
}

/* Para móvil, altura más grande */
@media (max-width: 767px) {
  .hero-fixed-height {
    min-height: 40vh;
  }
}

/* Si quieres además centrar verticalmente el contenido interno */
.hero-fixed-height .hero-inner {
  display: flex;
  align-items: center;
  gap: 1rem; /* opcional */
}
/* Quita las flechas por defecto del Swiper */
.swiper-button-prev, .swiper-button-next {
    display: none !important;
}

/* Ajuste visual extra para pantallas grandes */
@media (min-width: 1024px) {
    .custom-prev { left: -1.5rem; }
    .custom-next { right: -1.5rem; }
}
</style>
@endsection