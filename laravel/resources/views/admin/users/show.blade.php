{{-- resources/views/admin/users/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Usuario')
@section('subtitle', 'Información completa del usuario')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Usuarios</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-edit mr-2"></i> Editar Usuario
    </a>
    
    @if($user->id !== auth()->id())
        @if($user->is_active)
            <button type="button" 
                    onclick="openDeactivationModal({{ $user->id }}, '{{ $user->name }}')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                <i class="fas fa-user-slash mr-2"></i> Desactivar Usuario
            </button>
        @else
            <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-user-check mr-2"></i> Activar Usuario
                </button>
            </form>
        @endif
    @endif
    
    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button" 
                onclick="confirmAction('¿Estás seguro de eliminar este usuario?', () => this.form.submit())"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-trash mr-2"></i> Eliminar
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del usuario -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Perfil Público</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col items-center">
                    <img class="h-24 w-24 rounded-full mb-4" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->alias }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                    
                    <div class="mt-4 flex space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $user->role == 'admin' ? 'Administrador' : 'Usuario' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-star mr-1"></i> {{ $user->rating ?? '0' }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">RUT</dt>
                            <dd class="text-sm text-gray-900">{{ $user->rut }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="text-sm text-gray-900">{{ $user->phone ?? 'No proporcionado' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                            <dd class="text-sm text-gray-900">{{ $user->location ?? 'No proporcionada' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Registrado</dt>
                            <dd class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Verificado</dt>
                            <dd class="text-sm text-gray-900">
                                @if($user->email_verified_at)
                                    <span class="text-green-600">Sí ({{ $user->email_verified_at->format('d/m/Y') }})</span>
                                @else
                                    <span class="text-red-600">No</span>
                                @endif
                            </dd>
                        </div>
                           <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Estado</dt>
            <dd class="text-sm text-gray-900">
                @if($user->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Activo
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Inactivo
                    </span>
                @endif
            </dd>
        </div>
        @if($user->deactivated_at)
        <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Desactivado el</dt>
            <dd class="text-sm text-gray-900">{{ $user->deactivated_at->format('d/m/Y H:i') }}</dd>
        </div>
        @endif
        @if($user->deactivation_reason)
        <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Motivo</dt>
            <dd class="text-sm text-gray-900">{{ $user->deactivation_reason }}</dd>
        </div>
        @endif
                    </dl>
                </div>
                
                @if($user->bio)
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-500">Biografía</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->bio }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estadísticas y actividad -->
    <div class="lg:col-span-2">
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Productos Publicados</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->products_count }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-star text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Reseñas Recibidas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->reviews_count }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-paper-plane text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ofertas Enviadas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->sent_offers_count }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <i class="fas fa-inbox text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ofertas Recibidas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->received_offers_count }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="bg-white shadow rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <a href="#products" class="tab-link whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600" onclick="switchTab('products')">
                        Productos ({{ $user->products_count }})
                    </a>
                    <a href="#reviews" class="tab-link whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="switchTab('reviews')">
                        Reseñas ({{ $user->reviews_count }})
                    </a>
                    <a href="#offers-sent" class="tab-link whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="switchTab('offers-sent')">
                        Ofertas Enviadas ({{ $user->sent_offers_count }})
                    </a>
                    <a href="#offers-received" class="tab-link whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="switchTab('offers-received')">
                        Ofertas Recibidas ({{ $user->received_offers_count }})
                    </a>
                </nav>
            </div>

            <!-- Contenido de pestañas -->
            <div id="products" class="tab-content p-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Productos Publicados</h4>
                @if($products->count())
                    <div class="space-y-4">
                        @foreach($products as $product)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <img class="h-12 w-12 rounded-md object-cover" src="{{ $product->first_image_url }}" alt="{{ $product->title }}">
                                <div class="ml-4">
                                    <h5 class="text-sm font-medium text-gray-900">{{ $product->title }}</h5>
                                    <p class="text-sm text-gray-500">${{ number_format($product->price_reference, 0, ',', '.') }}</p>
                                    <div class="flex space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $product->status }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->published ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $product->published ? 'Publicado' : 'No publicado' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">El usuario no tiene productos publicados.</p>
                @endif
            </div>

            <div id="reviews" class="tab-content p-6 hidden">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Reseñas Recibidas</h4>
                @if($reviews->count())
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full" src="{{ $review->author->profile_photo_url }}" alt="{{ $review->author->name }}">
                                    <div class="ml-4">
                                        <h5 class="text-sm font-medium text-gray-900">{{ $review->author->name }}</h5>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">{{ $review->comment }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">El usuario no tiene reseñas.</p>
                @endif
            </div>

            <div id="offers-sent" class="tab-content p-6 hidden">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Ofertas Enviadas</h4>
                @if($sentOffers->count())
                    <div class="space-y-4">
                        @foreach($sentOffers as $offer)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900">Oferta #{{ $offer->id }}</h5>
                                    <p class="text-sm text-gray-500">Para: {{ $offer->toUser->name }}</p>
                                    <p class="text-sm text-gray-500">Producto solicitado: {{ $offer->productRequested->title }}</p>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $offer->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($offer->status == 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $offer->status }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $offer->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $sentOffers->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">El usuario no ha enviado ofertas.</p>
                @endif
            </div>

            <div id="offers-received" class="tab-content p-6 hidden">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Ofertas Recibidas</h4>
                @if($receivedOffers->count())
                    <div class="space-y-4">
                        @foreach($receivedOffers as $offer)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900">Oferta #{{ $offer->id }}</h5>
                                    <p class="text-sm text-gray-500">De: {{ $offer->fromUser->name }}</p>
                               @if($offer->productRequested)
    <p>Producto solicitado: {{ $offer->productRequested->title }}</p>
@else
    <p>Producto solicitado: Sin producto</p>
@endif
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $offer->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($offer->status == 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $offer->status }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $offer->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $receivedOffers->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">El usuario no ha recibido ofertas.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Modal de Desactivación -->
<div id="deactivationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Desactivar Usuario</h3>
            
            <form id="deactivationForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="deactivation_reason" class="block text-sm font-medium text-gray-700">
                        Motivo de desactivación
                    </label>
                    <textarea name="deactivation_reason" id="deactivation_reason" rows="3" 
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                              placeholder="Describe el motivo de la desactivación..."
                              required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeDeactivationModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                        <i class="fas fa-user-slash mr-2"></i> Desactivar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDeactivationModal(userId, userName) {
    const modal = document.getElementById('deactivationModal');
    const form = document.getElementById('deactivationForm');
    
    form.action = `/admin/users/${userId}/deactivate`;
    modal.classList.remove('hidden');
}

function closeDeactivationModal() {
    const modal = document.getElementById('deactivationModal');
    modal.classList.add('hidden');
}

// Cerrar modal al hacer click fuera
document.getElementById('deactivationModal').addEventListener('click', function(e) {
    if (e.target.id === 'deactivationModal') {
        closeDeactivationModal();
    }
});
</script>
<script>
    function switchTab(tabName) {
        // Ocultar todos los contenidos de pestañas
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Mostrar la pestaña seleccionada
        document.getElementById(tabName).classList.remove('hidden');

        // Actualizar estilos de los enlaces de pestañas
        document.querySelectorAll('.tab-link').forEach(link => {
            link.classList.remove('border-indigo-500', 'text-indigo-600');
            link.classList.add('border-transparent', 'text-gray-500');
        });

        // Activar el enlace actual
        event.currentTarget.classList.add('border-indigo-500', 'text-indigo-600');
        event.currentTarget.classList.remove('border-transparent', 'text-gray-500');
    }
</script>
@endsection