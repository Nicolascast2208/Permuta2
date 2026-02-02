@extends('layouts.app')

@section('title', 'Publicar Nuevo Producto')
@section('content')
<div class="container mx-auto">
<div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-3xl font-bold text-black">PUBLICA TU PRODUCTO</h2>
        <p class="text-gray-800 text-sm mt-1">Estás a un paso de publicar tu producto y hacerlo visible para miles de personas interesadas en permutar. </p>
    </div>
    
    <div class=" py-6">
     <livewire:product-form />
    </div>
</div>
@endsection