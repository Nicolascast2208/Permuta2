@extends('layouts.app')

@section('title', 'Editar Producto')
@section('content')
<div class="container mx-auto ">
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-3xl font-bold text-black">EDITAR PRODUCTO: {{ $product->title }}</h2>
        <p class="text-gray-800 text-sm mt-1">Actualiza los datos de tu publicación. Puedes cambiar el título, descripción, fotos, condición, ubicación y los productos que te interesan a cambio. </p>
    </div>

    
    <div class="">
        @livewire('product-form-edit', ['product' => $product])
    </div>
</div>
@endsection