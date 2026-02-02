@extends('layouts.app')

@section('title', 'Resultados de búsqueda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Resultados de búsqueda</h1>
        
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <span class="mr-2">
                @if($type === 'want')
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Buscando: Lo quiero</span>
                @else
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Buscando: Lo tengo</span>
                @endif
            </span>
            <span>para: <strong>"{{ $query }}"</strong></span>
        </div>
        
        @if($products->isEmpty())
            <div class="text-center py-8">
                <div class="text-5xl text-gray-300 mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 mb-2">No se encontraron resultados</h3>
                <p class="text-gray-500">Intenta con otros términos de búsqueda o ajusta los filtros.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $products->links('components.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection