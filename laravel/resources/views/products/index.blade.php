@extends('layouts.app')

@section('title', 'Listado de Productos')
@section('content')
<div class="container">
    <!-- Encabezado -->
    
    
    <!-- Componente Livewire con parámetros de búsqueda inicial -->
    <livewire:product-list 
        :initialSearch="$searchQuery ?? null" 
        :initialSearchType="$searchType ?? null" 
    />
</div>
@endsection