{{-- resources/views/admin/offers/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Gestión de Ofertas')
@section('subtitle', 'Administrar ofertas de permutas')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Ofertas</span>
    </div>
</li>
@endsection

@section('content')
<!-- Filtros -->
<div class="mb-6 bg-white shadow rounded-lg p-4">
    <form method="GET" action="{{ route('admin.offers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                   placeholder="Usuario, email..." 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition-colors duration-200">
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
            <select id="status" name="status" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition-colors duration-200">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Aceptada</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
                <option value="waiting_payment" {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Esperando pago</option>
               
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                <i class="fas fa-filter mr-2"></i> Filtrar
            </button>
        </div>
        
        <div class="flex items-end">
            <a href="{{ route('admin.offers.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                <i class="fas fa-redo mr-2"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-orange-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-orange-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $offers->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Aceptadas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $offers->where('status', 'accepted')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Rechazadas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $offers->where('status', 'rejected')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exchange-alt text-blue-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $offers->total() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de ofertas -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Lista de Ofertas
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ $offers->total() }} ofertas encontradas
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Mostrar:</span>
            <select class="border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-orange-500 focus:border-orange-500 text-sm transition-colors duration-200">
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Oferta
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Usuarios
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Productos
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($offers as $offer)
                <tr class="hover:bg-orange-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-orange-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">#{{ $offer->id }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($offer->complementary_amount > 0)
                                    Monto: ${{ number_format($offer->complementary_amount, 0, ',', '.') }}
                                    @else
                                    Sin monto complementario
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-user-circle text-orange-500 mr-2 text-sm"></i>
                                <span class="font-medium">{{ $offer->fromUser->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-gray-400 mr-2 text-sm"></i>
                                <span>{{ $offer->toUser->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 mb-1">
                            <i class="fas fa-gift text-orange-500 mr-1"></i>
                            <strong>Ofrece:</strong> 
                            @if($offer->productsOffered->count() > 0)
                                {{ $offer->productsOffered->count() }} producto(s)
                            @else
                                Sin productos ofrecidos
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-shopping-cart text-gray-400 mr-1"></i>
                            <strong>Solicita:</strong> {{ Str::limit($offer->productRequested->title ?? 'Producto no disponible', 25) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col space-y-2">
                            <!-- Estado de la oferta -->
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
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $currentStatus['color'] }}-100 text-{{ $currentStatus['color'] }}-800">
                                <i class="fas fa-{{ $currentStatus['icon'] }} mr-1"></i>
                                {{ $currentStatus['text'] }}
                            </span>
                            
           
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $offer->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $offer->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.offers.show', $offer) }}" 
                               class="text-orange-600 hover:text-orange-900 transition-colors duration-150 p-1 rounded hover:bg-orange-100"
                               title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                     
                            
                            <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmAction('¿Estás seguro de eliminar esta oferta?', () => this.form.submit())"
                                        class="text-red-600 hover:text-red-900 transition-colors duration-150 p-1 rounded hover:bg-red-100"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center justify-center py-12">
                            <i class="fas fa-exchange-alt text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg mb-2">No se encontraron ofertas</p>
                            <p class="text-gray-400 text-sm">Intenta ajustar los filtros de búsqueda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $offers->firstItem() ?? 0 }}</span>
                    a
                    <span class="font-medium">{{ $offers->lastItem() ?? 0 }}</span>
                    de
                    <span class="font-medium">{{ $offers->total() }}</span>
                    resultados
                </p>
            </div>
            <div>
                {{ $offers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Mejoras para los badges de estado */
    .status-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    
    /* Transiciones suaves para toda la tabla */
    table tr {
        transition: all 0.2s ease-in-out;
    }
</style>
@endpush