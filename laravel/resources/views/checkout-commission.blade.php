@extends('layouts.app')

@section('title', 'Pagar Comisión por Oferta')

@section('content')
<div class="container mx-auto">
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-3xl font-bold text-black">PAGAR COMISIÓN POR ACEPTAR OFERTA</h2>
        <p class="text-gray-800 text-sm mt-1">✅ Revisa que toda la información esté correcta antes de continuar.<br>
        🔒 Pago 100% seguro. </p>
    </div>
    <div class="max-w-7xl mx-auto">
        <div class="bg-gray-200 rounded-xl shadow-md overflow-hidden mb-4">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Resumen de la transacción</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6">
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                alt="{{ $product->title }}"
                                class="w-24 h-24 object-cover rounded-md">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-24 h-24"></div>
                        @endif
                        <div class="ml-4">
                            <h3 class="font-medium text-lg">{{ $product->title }}</h3>
                            <p class="text-gray-600">Producto por el cual estás pagando comisión</p>
                            <p class="text-sm text-gray-500">Precio referencial: ${{ number_format($product->price_reference, 0, ',', '.') }} CLP</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg mb-3">Detalles del pago</h3>
                        <div class="bg-white rounded-lg p-4">
                            <div class="flex justify-between mb-2">
                                <span>Comisión por aceptar oferta (5%)</span>
                                <span>${{ number_format($commission, 0, ',', '.') }} CLP</span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                                <span>Total a pagar</span>
                                <span>${{ number_format($commission, 0, ',', '.') }} CLP</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="bg-white rounded-lg p-6 border">
                        <h3 class="font-semibold text-lg mb-4">Resumen de compra</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Producto:</span>
                                <span class="font-medium">{{ $product->title }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span>Comisión:</span>
                                <span class="font-medium">${{ number_format($commission, 0, ',', '.') }} CLP</span>
                            </div>
                            
                            <div class="flex justify-between text-lg font-semibold pt-3 border-t">
                                <span>Total:</span>
                                <span>${{ number_format($commission, 0, ',', '.') }} CLP</span>
                            </div>
                        </div>
                        
                        <form action="{{ route('checkout.process-commission', $offer) }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-full shadow-md hover:shadow-lg transition border-2 border-white w-full">
                                Pagar ahora con MercadoPago
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection