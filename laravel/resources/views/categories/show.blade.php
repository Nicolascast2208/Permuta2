@extends('layouts.app')

@section('content')
<div>
    <!-- Encabezado -->
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <div class="flex items-center">
            <h2 class="text-3xl font-bold text-black inline-block">{{ $category->name }}</h2>
        </div>
        <p class="text-gray-800 text-sm mt-2">Productos disponibles en esta categoría</p>
    </div>
    
    <!-- Usar el componente Livewire -->
    <livewire:categories :category="$category" />
</div>
@endsection