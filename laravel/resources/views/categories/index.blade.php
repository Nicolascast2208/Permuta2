@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Explora por Categorías</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Descubre productos disponibles para intercambio organizados por categorías. 
            Selecciona una subcategoría para ver todos los productos relacionados.
        </p>
    </div>

    <!-- Grid de categorías -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($categories as $parentCategory)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300">
      <!-- Encabezado de la categoría padre con imagen -->
        <a href="{{ route('categories.show', $parentCategory->slug) }}" 
       class="fondo-amarillo px-6 py-4 flex items-center hover:bg-yellow-400 transition-colors duration-200">
        @if($parentCategory->image)
            <img src="{{ asset('storage/' . $parentCategory->image) }}" alt="{{ $parentCategory->name }}" class="w-auto h-10 mr-3 object-cover">
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
        @endif
        <h2 class="text-xl font-bold text-black">{{ $parentCategory->name }}</h2>
    </a>
            
            <!-- Lista de subcategorías -->
            <div class="p-6">
                <div class="grid grid-cols-1 gap-3">
                    @foreach($parentCategory->children as $subcategory)
                    <a href="{{ route('categories.show', $subcategory->slug) }}" 
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-50 transition-colors duration-200 group">
                        <span class="text-gray-700 group-hover:text-blue-600 font-medium">{{ $subcategory->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    @endforeach
                </div>
                
                <!-- Ver todos los productos de esta categoría -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('categories.show', $parentCategory->slug) }}" 
                       class="flex items-center justify-center text-blue-600 hover:text-blue-800 font-medium text-sm">
                        <span>Ver Todo En {{ $parentCategory->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection