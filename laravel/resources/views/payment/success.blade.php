@extends('layouts.app')

@section('title', '¡Pago Exitoso!')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header verde con icono de éxito -->
            <div class="bg-green-500 py-8 px-6 text-center">
                <div class="mx-auto w-24 h-24 bg-white rounded-full flex items-center justify-center mb-4">
                    <svg class="h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white">¡Pago Exitoso!</h1>
                <p class="mt-2 text-green-100">Tu transacción se ha completado correctamente</p>
            </div>
            
            <!-- Cuerpo de la confirmación -->
            <div class="py-8 px-6 sm:px-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Detalles de la transacción -->

                    
                    <!-- Detalles del producto -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detalles del Producto</h2>
                        <div class="flex items-center border rounded-lg p-4 bg-gray-50">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                     alt="{{ $product->title }}"
                                     class="w-24 h-24 object-cover rounded-lg">
                            @else
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-24 h-24"></div>
                            @endif
                            <div class="ml-4">
                                <h3 class="font-medium text-lg">{{ $product->title }}</h3>
                                <p class="text-gray-600 mt-1">Publicado por: {{ $product->user->alias }}</p>
                                <p class="text-gray-600">Valor de referencia: ${{ number_format($product->price_reference, 0, ',', '.') }} CLP</p>
                                <p class="mt-2">
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        Publicación activa
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <h3 class="font-semibold text-blue-800 mb-2">¿Qué sigue?</h3>
                            <ul class="list-disc pl-5 text-blue-700 space-y-1">
                                <li>Tu publicación ya está activa y visible para otros usuarios</li>
                                <li>Recibirás notificaciones cuando alguien haga una oferta</li>
                                <li>No pagarás comisión al aceptar un trueque</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('products.show', $product) }}" 
                       class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ver Publicación
                    </a>
                    
                    <a href="{{ route('dashboard.my-products') }}" 
                       class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Ir a Mi Perfil
                    </a>
                    
                    <a href="{{ route('products.create') }}" 
                       class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Publicar Otro Producto
                    </a>
                </div>
            </div>
            
            <!-- Pie de página -->
            <div class="bg-gray-50 px-6 py-4 text-center">
                <p class="text-sm text-gray-500">
                    ¿Necesitas ayuda? 
                    <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-800">
                        Contáctanos
                    </a> 
                    | 
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                        Volver al inicio
                    </a>
                </p>
            </div>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Recibirás un comprobante de pago por email en los próximos minutos</p>
        </div>
    </div>
</div>
@endsection