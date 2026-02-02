@extends('layouts.app')

@section('title', 'Pagar Comisión por Productos Ofrecidos')

@section('content')
<div class="container mx-auto">
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-3xl font-bold text-black">PAGAR COMISIÓN POR PRODUCTOS OFRECIDOS</h2>
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
                    <h3 class="font-semibold text-lg mb-4">Productos que estás ofreciendo</h3>
                    
                    <div class="space-y-4">
                        @foreach($unpaidProducts as $product)
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                        alt="{{ $product->title }}"
                                        class="w-20 h-20 object-cover rounded-md">
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-20 h-20 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-4 flex-1">
                                    <h4 class="font-medium text-lg">{{ $product->title }}</h4>
                                    <p class="text-gray-600">Precio referencial: ${{ number_format($product->price_reference, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">Comisión individual: ${{ number_format($product->price_reference * 0.05, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg mb-3">Detalles del pago</h3>
                        <div class="bg-white rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Número de productos:</span>
                                    <span>{{ $unpaidProducts->count() }} producto(s)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Valor total de productos:</span>
                                    <span>${{ number_format($unpaidProducts->sum('price_reference'), 0, ',', '.') }} CLP</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Comisión (5%):</span>
                                    <span>${{ number_format($commission, 0, ',', '.') }} CLP</span>
                                </div>
                            </div>
                            <div class="flex justify-between font-semibold text-lg pt-3 border-t mt-3">
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
                                <span>Productos a pagar:</span>
                                <span class="font-medium">{{ $unpaidProducts->count() }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span>Valor productos:</span>
                                <span class="font-medium">${{ number_format($unpaidProducts->sum('price_reference'), 0, ',', '.') }} CLP</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span>Comisión (5%):</span>
                                <span class="font-medium">${{ number_format($commission, 0, ',', '.') }} CLP</span>
                            </div>
                            
                            <div class="flex justify-between text-lg font-semibold pt-3 border-t">
                                <span>Total:</span>
                                <span>${{ number_format($commission, 0, ',', '.') }} CLP</span>
                            </div>
                        </div>
                        
                        <form action="{{ route('checkout.process-commission-offered', $offer) }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition border-2 border-white w-full text-lg">
                                Pagar ahora con MercadoPago
                            </button>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">
                                Al pagar, estarás pagando la comisión por los {{ $unpaidProducts->count() }} producto(s) ofrecidos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection