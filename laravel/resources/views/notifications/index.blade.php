@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<div class="container mx-auto px-4 py-8 fondo-gris rounded-xl">
    <div class="mb-6 text-center md:text-left">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tus Notificaciones</h1>
        <p class="text-gray-600">Aquí puedes ver todas tus notificaciones</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($notifications->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes notificaciones</h3>
            <p class="mt-1 text-gray-500">Cuando recibas nuevas notificaciones, aparecerán aquí.</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 transition ease-in-out duration-150">
                    Explorar productos
                </a>
            </div>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <div>
                    <p class="text-sm text-gray-600">
                        Mostrando {{ $notifications->count() }} de {{ $notifications->total() }} notificaciones
                        @php
                            $unreadCount = auth()->user()->notifications()->where('read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="font-semibold">({{ $unreadCount }} sin leer)</span>
                        @endif
                    </p>
                </div>
                @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Marcar todas como leídas
                    </button>
                </form>
                @endif
            </div>
            
            <ul class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    @php
                        // Obtener la URL de redirección según el tipo de notificación
                        $redirectUrl = '#';
                        $notificationData = $notification->data ?? [];
                        
                        switch($notification->type) {
                            case 'product_created':
                                $redirectUrl = isset($notificationData['product_id']) 
                                    ? route('products.show', $notificationData['product_id'])
                                    : route('products.index');
                                break;
                                
                            case 'offer_received':
                                $redirectUrl = isset($notificationData['offer_id']) 
                                    ? route('dashboard.received-offers', $notificationData['offer_id'])
                                    : route('dashboard.received-offers');
                                break;
                                
                            case 'trade_accepted':
                                $redirectUrl = isset($notificationData['trade_id']) 
                                    ? route('dashboard.trades', $notificationData['trade_id'])
                                    : route('dashboard.trades');
                                break;
                                
                            case 'offer_accepted':
                                $redirectUrl = isset($notificationData['trade_id']) 
                                    ? route('dashboard.trades', $notificationData['trade_id'])
                                    : route('dashboard.trades');
                                break;
                                
                            case 'payment_required':
                                $redirectUrl = route('dashboard.sent-offers') . '?status=waiting_payment';
                                break;
                                
                            case 'product_question':
                            case 'question_answered':
                                $redirectUrl = isset($notificationData['product_id']) 
                                    ? route('products.show', $notificationData['product_id']). '#preguntas'
                                    : route('products.index');
                                break;
                                
                            case 'intermediate_question':
                            case 'intermediate_answer':
                                // Redirigir a la página de intermediación de la oferta
                                if (isset($notificationData['offer_id'])) {
                                    $redirectUrl = route('offer.intermediate', $notificationData['offer_id']);
                                }
                                // Si por alguna razón no hay offer_id, redirigir al producto
                                elseif (isset($notificationData['product_id'])) {
                                    $redirectUrl = route('products.show', $notificationData['product_id']);
                                }
                                break;
                                
                            default:
                                $redirectUrl = route('notifications.index');
                                break;
                        }
                    @endphp
                    
                    <li class="p-6 hover:bg-gray-50 transition {{ $notification->read ? 'bg-gray-50' : 'bg-white' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start">
                                <div class="mr-4 mt-1">
                                    @if($notification->read)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ $redirectUrl }}" class="text-gray-800 hover:text-blue-600 transition">
                                        {{ $notification->message }}
                                    </a>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if(!$notification->read)
                                <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center text-sm bg-white text-gray-700 px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Marcar como leída
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
            
            <!-- Paginación -->
            <div class="bg-white px-6 py-4 border-t">
                {{ $notifications->links() }}
            </div>
        </div>
    @endif
</div>
@endsection