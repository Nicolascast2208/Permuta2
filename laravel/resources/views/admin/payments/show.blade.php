{{-- resources/views/admin/payments/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Pago')
@section('subtitle', 'Información completa del pago')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.payments.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Pagos</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle</span>
    </div>
</li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del pago -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Pago</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Detalles del Pago</h4>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">ID</dt>
                                <dd class="text-sm text-gray-900">#{{ $payment->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Orden de compra</dt>
                                <dd class="text-sm text-gray-900">{{ $payment->buy_order }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Tipo de pago</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($payment->type == 'publication') bg-blue-100 text-blue-800
                                        @elseif($payment->type == 'commission_requested') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $payment->formatted_type }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Método de pago</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($payment->payment_method) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Monto</dt>
                                <dd class="text-sm font-medium text-gray-900">${{ number_format($payment->amount, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Estado y Fechas</h4>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Estado</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($payment->status == 'approved') bg-green-100 text-green-800
                                        @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($payment->status == 'failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $payment->formatted_status }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Código de autorización</dt>
                                <dd class="text-sm text-gray-900">{{ $payment->authorization_code ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Tarjeta</dt>
                                <dd class="text-sm text-gray-900">{{ $payment->card_number ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Fecha de transacción</dt>
                                <dd class="text-sm text-gray-900">{{ $payment->transaction_date ? $payment->transaction_date->format('d/m/Y H:i') : 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Creado</dt>
                                <dd class="text-sm text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Información de Transbank -->
                @if($payment->response_data)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Respuesta de Transbank</h4>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <pre class="text-sm text-gray-600 whitespace-pre-wrap">{{ json_encode($payment->response_data, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Información relacionada -->
        @if($payment->product || $payment->offer)
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Información Relacionada</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($payment->product)
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Producto</h4>
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                        <img class="h-12 w-12 rounded-md object-cover mr-3" src="{{ $payment->product->first_image_url }}" alt="{{ $payment->product->title }}">
                        <div class="flex-1">
                            <h5 class="text-sm font-medium text-gray-900">{{ $payment->product->title }}</h5>
                            <p class="text-sm text-gray-500">${{ number_format($payment->product->price_reference, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">Estado: {{ $payment->product->status }}</p>
                        </div>
                        <a href="{{ route('admin.products.show', $payment->product) }}" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                @endif

                @if($payment->offer)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Oferta</h4>
                    <div class="p-3 border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Oferta #{{ $payment->offer->id }}</h5>
                                <p class="text-sm text-gray-500">De: {{ $payment->offer->fromUser->name }}</p>
                                <p class="text-sm text-gray-500">Para: {{ $payment->offer->toUser->name }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $payment->offer->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($payment->offer->status == 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $payment->offer->status }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('admin.offers.show', $payment->offer) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Información del usuario -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Usuario</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full mr-3" src="{{ $payment->user->profile_photo_url }}" alt="{{ $payment->user->name }}">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $payment->user->email }}</p>
                        <p class="text-xs text-gray-400">{{ $payment->user->alias }}</p>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">RUT</span>
                        <span class="text-gray-900">{{ $payment->user->rut }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Teléfono</span>
                        <span class="text-gray-900">{{ $payment->user->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Registrado</span>
                        <span class="text-gray-900">{{ $payment->user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.users.show', $payment->user) }}" class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-user mr-1"></i> Perfil
                    </a>
                    <a href="{{ route('admin.payments.by-user', $payment->user) }}" class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-credit-card mr-1"></i> Pagos
                    </a>
                </div>
            </div>
        </div>

        <!-- Resumen de pagos del usuario -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Resumen de Pagos</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @php
                    $userPayments = $payment->user->payments()->approved()->get();
                    $totalUserPayments = $userPayments->count();
                    $totalUserAmount = $userPayments->sum('amount');
                @endphp
                
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Total de pagos</dt>
                        <dd class="text-sm text-gray-900">{{ $totalUserPayments }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Monto total</dt>
                        <dd class="text-sm text-gray-900">${{ number_format($totalUserAmount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Último pago</dt>
                        <dd class="text-sm text-gray-900">
                            @if($userPayments->count() > 0)
                                {{ $userPayments->first()->created_at->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection