{{-- resources/views/admin/offers/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Oferta')
@section('subtitle', 'Información completa de la oferta')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.offers.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600 transition-colors duration-200">Ofertas</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle #{{ $offer->id }}</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    @if($offer->status == 'pending')
    <form action="{{ route('admin.offers.accept', $offer) }}" method="POST" class="inline">
        @csrf
        <button type="submit" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                onclick="return confirm('¿Estás seguro de aceptar esta oferta?')">
            <i class="fas fa-check-circle mr-2"></i> Aceptar Oferta
        </button>
    </form>
    <form action="{{ route('admin.offers.reject', $offer) }}" method="POST" class="inline">
        @csrf
        <button type="submit" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                onclick="return confirm('¿Estás seguro de rechazar esta oferta?')">
            <i class="fas fa-times-circle mr-2"></i> Rechazar Oferta
        </button>
    </form>
    @endif
    
    <a href="{{ route('admin.offers.index') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información de la oferta -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Header de la oferta -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Información de la Oferta</h3>
                    <p class="mt-1 text-sm text-gray-500">ID: #{{ $offer->id }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @php
                        $statusConfig = [
                            'pending' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Pendiente'],
                            'accepted' => ['color' => 'green', 'icon' => 'check-circle', 'text' => 'Aceptada'],
                            'rejected' => ['color' => 'red', 'icon' => 'times-circle', 'text' => 'Rechazada'],
                            'waiting_payment' => ['color' => 'blue', 'icon' => 'credit-card', 'text' => 'Esperando Pago'],
                            'completed' => ['color' => 'green', 'icon' => 'check-double', 'text' => 'Completada'],
                            'cancelled' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Cancelada']
                        ];
                        
                        $currentStatus = $statusConfig[$offer->status] ?? ['color' => 'gray', 'icon' => 'question', 'text' => $offer->status];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $currentStatus['color'] }}-100 text-{{ $currentStatus['color'] }}-800">
                        <i class="fas fa-{{ $currentStatus['icon'] }} mr-1"></i>
                        {{ $currentStatus['text'] }}
                    </span>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <!-- Usuarios involucrados -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Usuario oferente -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-orange-800 mb-3 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Usuario Oferente
                        </h4>
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-full mr-4 border-2 border-orange-300" 
                                 src="{{ $offer->fromUser->profile_photo_url }}" 
                                 alt="{{ $offer->fromUser->name }}"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($offer->fromUser->name) }}&color=7c3aed&background=ede9fe'">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $offer->fromUser->name }}</p>
                                <p class="text-xs text-gray-600">{{ $offer->fromUser->email }}</p>
                                <p class="text-xs text-gray-500 mt-1">Miembro desde {{ $offer->fromUser->created_at->format('m/Y') }}</p>
                            </div>
                            <a href="{{ route('admin.users.show', $offer->fromUser) }}" 
                               class="text-orange-600 hover:text-orange-800 transition-colors duration-200"
                               title="Ver perfil">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Usuario receptor -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-inbox mr-2"></i> Usuario Receptor
                        </h4>
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-full mr-4 border-2 border-blue-300" 
                                 src="{{ $offer->toUser->profile_photo_url }}" 
                                 alt="{{ $offer->toUser->name }}"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($offer->toUser->name) }}&color=2563eb&background=dbeafe'">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $offer->toUser->name }}</p>
                                <p class="text-xs text-gray-600">{{ $offer->toUser->email }}</p>
                                <p class="text-xs text-gray-500 mt-1">Miembro desde {{ $offer->toUser->created_at->format('m/Y') }}</p>
                            </div>
                            <a href="{{ route('admin.users.show', $offer->toUser) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                               title="Ver perfil">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Productos involucrados -->
                <div class="space-y-6 mb-8">
                    <!-- Productos ofrecidos (múltiples) -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-gift text-orange-500 mr-2"></i>
                            Productos Ofrecidos ({{ $offer->productsOffered->count() }})
                        </h4>
                        @if($offer->productsOffered->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($offer->productsOffered as $productOffered)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-300 transition-colors duration-200">
                                    <div class="flex items-start">
                                        <img class="h-16 w-16 rounded-md object-cover mr-4 flex-shrink-0" 
                                             src="{{ $productOffered->first_image_url }}" 
                                             alt="{{ $productOffered->title }}"
                                             onerror="this.src='https://via.placeholder.com/80x80/f3f4f6/9ca3af?text=Imagen+No+Disponible'">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $productOffered->title }}</p>
                                            <p class="text-xs text-gray-500 mt-1">${{ number_format($productOffered->price_reference, 0, ',', '.') }}</p>
                                            <div class="flex items-center mt-2 space-x-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $productOffered->was_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    <i class="fas fa-{{ $productOffered->was_paid ? 'check' : 'clock' }} mr-1"></i>
                                                    {{ $productOffered->was_paid ? 'Pagado' : 'Pendiente' }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $productOffered->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $productOffered->status == 'active' ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.products.show', $productOffered) }}" 
                                           class="text-orange-600 hover:text-orange-800 transition-colors duration-200 ml-2"
                                           title="Ver producto">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 text-center">
                                <i class="fas fa-box-open text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">No hay productos ofrecidos</p>
                            </div>
                        @endif
                    </div>

                    <!-- Producto solicitado -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-shopping-cart text-blue-500 mr-2"></i>
                            Producto Solicitado
                        </h4>
                        @if($offer->productRequested)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                            <div class="flex items-start">
                                <img class="h-16 w-16 rounded-md object-cover mr-4 flex-shrink-0" 
                                     src="{{ $offer->productRequested->first_image_url }}" 
                                     alt="{{ $offer->productRequested->title }}"
                                     onerror="this.src='https://via.placeholder.com/80x80/f3f4f6/9ca3af?text=Imagen+No+Disponible'">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $offer->productRequested->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">${{ number_format($offer->productRequested->price_reference, 0, ',', '.') }}</p>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $offer->productRequested->was_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            <i class="fas fa-{{ $offer->productRequested->was_paid ? 'check' : 'clock' }} mr-1"></i>
                                            {{ $offer->productRequested->was_paid ? 'Pagado' : 'Pendiente' }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $offer->productRequested->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $offer->productRequested->status == 'active' ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.products.show', $offer->productRequested) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200 ml-2"
                                   title="Ver producto">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 text-center">
                            <i class="fas fa-exclamation-triangle text-gray-300 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">Producto no disponible o eliminado</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Detalles de la oferta -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                            Detalles de la Oferta
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-600">Monto complementario</dt>
                                <dd class="text-sm font-semibold text-gray-900">
                                    @if($offer->complementary_amount > 0)
                                    ${{ number_format($offer->complementary_amount, 0, ',', '.') }}
                                    @else
                                    <span class="text-gray-400">Sin monto</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-600">Estado</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $currentStatus['color'] }}-100 text-{{ $currentStatus['color'] }}-800">
                                        <i class="fas fa-{{ $currentStatus['icon'] }} mr-1"></i>
                                        {{ $currentStatus['text'] }}
                                    </span>
                                </dd>
                            </div>
                          
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt text-orange-500 mr-2"></i>
                            Fechas
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-600">Creada</dt>
                                <dd class="text-sm text-gray-900">
                                    <div>{{ $offer->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $offer->created_at->format('H:i') }}</div>
                                </dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-600">Actualizada</dt>
                                <dd class="text-sm text-gray-900">
                                    <div>{{ $offer->updated_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $offer->updated_at->format('H:i') }}</div>
                                </dd>
                            </div>
                            @if($offer->status == 'accepted' && $offer->accepted_at)
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-600">Aceptada</dt>
                                <dd class="text-sm text-gray-900">
                                    <div>{{ $offer->accepted_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $offer->accepted_at->format('H:i') }}</div>
                                </dd>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Comentario -->
                @if($offer->comment)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-comment text-orange-500 mr-2"></i>
                        Comentario del oferente
                    </h4>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <p class="text-sm text-orange-800 italic">"{{ $offer->comment }}"</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Preguntas intermedias -->
        @if($offer->intermediateQuestions->count())
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-question-circle text-orange-500 mr-2"></i>
                    Preguntas Intermedias ({{ $offer->intermediateQuestions->count() }})
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    @foreach($offer->intermediateQuestions as $question)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-200 transition-colors duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full mr-3 border-2 border-orange-200" 
                                     src="{{ $question->user->profile_photo_url }}" 
                                     alt="{{ $question->user->name }}"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($question->user->name) }}&color=7c3aed&background=ede9fe'">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $question->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $question->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 mb-4 bg-gray-50 p-3 rounded-md">{{ $question->question }}</p>
                        
                        @if($question->answer)
                        <div class="ml-6 pl-4 border-l-2 border-green-200 py-3 bg-green-50 rounded-md">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full mr-2 border-2 border-green-200" 
                                         src="{{ $question->answerer->profile_photo_url }}" 
                                         alt="{{ $question->answerer->name }}"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($question->answerer->name) }}&color=059669&background=d1fae5'">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $question->answerer->name }}</p>
                                       @if($question->answered_at)
    <p class="text-xs text-gray-500">{{ $question->answered_at->diffForHumans() }}</p>
@endif
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700">{{ $question->answer }}</p>
                        </div>
                        @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <p class="text-xs text-yellow-800 italic flex items-center">
                                <i class="fas fa-clock mr-1"></i> Pendiente de respuesta
                            </p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar - Chat y acciones -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Chat -->
        @if($offer->chat)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-comments text-orange-500 mr-2"></i>
                    Chat de la Oferta
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-3 max-h-80 overflow-y-auto mb-4">
                    @foreach($offer->chat->messages->take(8) as $message)
                    <div class="flex {{ $message->user_id == $offer->from_user_id ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs rounded-lg px-3 py-2 {{ $message->user_id == $offer->from_user_id ? 'bg-gray-100 border border-gray-200' : 'bg-orange-100 border border-orange-200' }}">
                            <p class="text-sm text-gray-900">{{ $message->body }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <a href="{{ route('admin.chats.show', $offer->chat) }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                        <i class="fas fa-comments mr-2"></i> Ver chat completo
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Acciones rápidas -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-bolt text-orange-500 mr-2"></i>
                    Acciones Rápidas
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-3">
                    @if($offer->status == 'pending')
                    <form action="{{ route('admin.offers.accept', $offer) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                onclick="return confirm('¿Estás seguro de aceptar esta oferta?')">
                            <i class="fas fa-check-circle mr-2"></i> Aceptar Oferta
                        </button>
                    </form>
                    <form action="{{ route('admin.offers.reject', $offer) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                onclick="return confirm('¿Estás seguro de rechazar esta oferta?')">
                            <i class="fas fa-times-circle mr-2"></i> Rechazar Oferta
                        </button>
                    </form>
                    @endif

                    <!-- Acciones adicionales -->
                    <div class="pt-3 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-2">
                          <!--  <a href="{{ route('admin.offers.edit', $offer) }}" 
                               class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a>-->
                            <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmAction('¿Estás seguro de eliminar esta oferta? Esta acción no se puede deshacer.', () => this.form.submit())"
                                        class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-chart-bar text-orange-500 mr-2"></i>
                    Resumen
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total productos:</span>
                        <span class="font-medium text-gray-900">{{ $offer->productsOffered->count() + 1 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Valor ofrecido:</span>
                        <span class="font-medium text-gray-900">
                            ${{ number_format($offer->productsOffered->sum('price_reference'), 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Valor solicitado:</span>
                        <span class="font-medium text-gray-900">
                            ${{ number_format($offer->productRequested->price_reference ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    @if($offer->complementary_amount > 0)
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="text-gray-600">Monto complementario:</span>
                        <span class="font-medium text-orange-600">
                            +${{ number_format($offer->complementary_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="text-gray-700 font-medium">Diferencia:</span>
                        <span class="font-medium {{ ($offer->productsOffered->sum('price_reference') + $offer->complementary_amount - ($offer->productRequested->price_reference ?? 0)) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($offer->productsOffered->sum('price_reference') + $offer->complementary_amount - ($offer->productRequested->price_reference ?? 0), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Mejoras visuales adicionales */
    .message-bubble {
        transition: all 0.2s ease-in-out;
    }
    
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush