@extends('layouts.app')

@section('title', 'Oferta Enviada - Permuta2')
@section('content')
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado de éxito -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">¡Oferta Enviada con Éxito!</h1>
            <p class="text-gray-600 text-lg">Tu oferta ha sido enviada al vendedor. Serás notificado cuando responda.</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Barra de progreso -->
            <div class="bg-blue-600 h-2 w-full"></div>

            <div class="p-6">
                <!-- Resumen de la oferta -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Producto solicitado -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Producto que solicitas
                        </h3>
                        <div class="flex items-center space-x-3">
                            <img src="{{ $offer->productRequested->first_image_url }}" 
                                 alt="{{ $offer->productRequested->title }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $offer->productRequested->title }}</h4>
                                <p class="text-sm text-gray-600">${{ number_format($offer->productRequested->price_reference, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Productos ofrecidos -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            Tus productos ofrecidos
                        </h3>
                        <div class="space-y-2">
                            @foreach($offer->productsOffered as $product)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $product->first_image_url }}" 
                                     alt="{{ $product->title }}"
                                     class="w-12 h-12 object-cover rounded">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $product->title }}</h4>
                                    <p class="text-xs text-gray-600">${{ number_format($product->price_reference, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($offer->complementary_amount > 0)
                            <div class="flex items-center space-x-3 pt-2 border-t">
                                <div class="w-12 h-12 bg-yellow-100 rounded flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Complemento en dinero</h4>
                                    <p class="text-xs text-gray-600">${{ number_format($offer->complementary_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Información del estado -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-800">¿Qué sigue ahora?</h4>
                            <ul class="text-sm text-blue-700 mt-2 space-y-1">
                                <li>• El vendedor ha sido notificado de tu oferta</li>
                                <li>• Puedes ver el estado de tu oferta en tu panel de control</li>
                                <li>• Serás notificado cuando el vendedor responda</li>
                                <li>• Si la oferta es aceptada, podrán coordinar el intercambio</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t">
                    <a href="{{ route('products.show', $offer->productRequested) }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-800 font-medium rounded-lg hover:bg-gray-200 transition text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al producto
                    </a>
                    <a href="{{ route('dashboard.sent-offers') }}" 
                       class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition text-center flex items-center justify-center shadow-md shadow-blue-100">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ver mis ofertas
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar más productos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection