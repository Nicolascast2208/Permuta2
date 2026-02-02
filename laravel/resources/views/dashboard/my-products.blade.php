@extends('layouts.app')

@section('title', 'Mis Productos')

@section('content')
<div class="container mx-auto px-4 py-8 fondo-gris rounded-xl">
    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Mis Productos</h1>
            <p class="text-gray-600">Administra tus publicaciones activas y pendientes</p>
        </div>
        <a href="{{ route('products.createx') }}"
           class="flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:ring ring-blue-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Publicar Nuevo Producto</span>
        </a>
    </div>

    {{-- Barra de búsqueda / filtros --}}
    <form action="{{ route('dashboard.my-products') }}" method="GET" class="bg-white rounded-xl shadow-sm p-5 mb-8 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar productos..."
                    class="w-full border border-gray-300 rounded-xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
            </div>
            
            <div>
                <select name="category" class="w-full border border-gray-300 rounded-xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <select name="status" class="w-full border border-gray-300 rounded-xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                    <option value="">Todos los estados</option>
                    <option value="available" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirado</option>
                    <option value="paired" {{ request('status') == 'paired' ? 'selected' : '' }}>Permutado</option>
                </select>
            </div>
        </div>
        
        <div class="mt-4 flex justify-end gap-3">
            <button type="submit"
                class="flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:ring ring-blue-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Aplicar Filtros</span>
            </button>
            
            @if(request()->has('search') || request()->has('category') || request()->has('status'))
            <a href="{{ route('dashboard.my-products') }}"
               class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2.5 px-6 rounded-full transition font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Limpiar Filtros</span>
            </a>
            @endif
        </div>
    </form>

    {{-- Mensaje si no hay productos --}}
    @if($products->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="text-xl font-medium text-gray-700 mt-4">No se encontraron productos</h3>
            <p class="text-gray-500 mt-2 mb-6">Intenta ajustar tus filtros de búsqueda o crear un nuevo producto</p>
            <a href="{{ route('products.createx') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-full transition">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
               </svg>
               <span>Crear nueva publicación</span>
            </a>
        </div>
    @else
        {{-- Lista de productos --}}
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="grid grid-cols-1 divide-y">
                @foreach($products as $product)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            
                            <!-- Información del producto -->
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="relative mr-4">
                                        @if($product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                                alt="{{ $product->title }}"
                                                class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                                        @else
                                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-24 h-24 flex-shrink-0 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Badge de estado -->
                                        @php
                                            $statusLabels = [
                                                'available' => 'Activo',
                                                'pending' => 'Pendiente',
                                                'expired' => 'Vencido',
                                                'paired' => 'Permutado'
                                            ];
                                        @endphp
                                        <div class="absolute top-2 right-2 px-3 py-1 rounded-full text-xs font-semibold
                                            @if($product->status === 'active') bg-green-100 text-green-800
                                            @elseif($product->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($product->status === 'expired') bg-red-100 text-red-800
                                            @elseif($product->status === 'paired') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $statusLabels[$product->status] ?? ucfirst($product->status) }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-lg">
                                            <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600">
                                                {{ $product->title }}
                                            </a>
                                        </h3>
                                        
                                        <div class="flex items-center text-sm text-gray-600 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                            <span>Categoría: {{ $product->category->name ?? 'General' }}</span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-500 mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Expira: {{ $product->expiration_date->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Acciones -->
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto mt-4 sm:mt-0 items-center justify-center">
    @if($product->status === 'paired' || $product->status === 'permutado')
        <a href="{{ route('dashboard.trades') }}"
           class="flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:ring ring-green-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span>Ver Permutas</span>
        </a>
    @else
        <a href="{{ route('products.edit', $product) }}"
           class="flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:ring ring-blue-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Editar</span>
        </a>

        @if($product->status === 'pending')
            <a href="{{ route('checkout.show', $product) }}"
               class="flex items-center justify-center px-4 py-2 bg-yellow-500 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:ring ring-yellow-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Pagar Publicación</span>
            </a>
        @endif

        <form action="{{ route('products.destroy', $product) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-gray-600 underline hover:text-gray-800 font-semibold text-xs uppercase tracking-widest transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Eliminar</span>
            </button>
        </form>
    @endif
</div>

                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="bg-white px-6 py-4 border-t">
                {{ $products->appends(request()->query())->links('components.pagination') }}
            </div>
        </div>
    @endif
</div>
@endsection