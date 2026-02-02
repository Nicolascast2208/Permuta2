@extends('layouts.app')

@section('title', 'Pago Fallido')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header rojo con icono de error -->
            <div class="bg-red-500 py-10 px-6 text-center">
                <div class="mx-auto w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4">
                    <svg class="h-14 w-14 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Pago Fallido</h1>
                <p class="mt-2 text-red-100">No pudimos procesar tu pago</p>
            </div>
            
            <!-- Cuerpo del mensaje -->
            <div class="py-8 px-6 sm:px-10 text-center">
                <p class="text-gray-700 mb-6">
                    Lo sentimos, tu transacción no pudo ser completada. Por favor intenta nuevamente o utiliza otro método de pago.
                </p>
                
                <div class="mt-6 bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                    <h3 class="font-semibold text-yellow-800 mb-2">¿Necesitas ayuda?</h3>
                    <ul class="text-sm text-yellow-700">
                        <li class="mb-1">• Verifica los fondos de tu tarjeta</li>
                        <li class="mb-1">• Asegúrate de que los datos sean correctos</li>
                        <li class="mb-1">• Intenta con otra tarjeta o método de pago</li>
                    </ul>
                </div>
                
                <!-- Botones de acción -->
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('dashboard.my-products') }}" 
                       class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg shadow-md transition">
                        Volver a Mis Productos
                    </a>
                    
                    <a href="{{ route('contact') }}" 
                       class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition">
                        Contactar Soporte
                    </a>
                </div>
            </div>
            
            <!-- Pie de página -->
            <div class="bg-gray-50 px-6 py-4 text-center">
                <p class="text-sm text-gray-500">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                        Volver al inicio
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection