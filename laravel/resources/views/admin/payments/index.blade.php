{{-- resources/views/admin/payments/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Gestión de Pagos')
@section('subtitle', 'Administrar todos los pagos de la plataforma')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Pagos</span>
    </div>
</li>
@endsection

@section('content')
<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-receipt text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Pagos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Aprobados</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-clock text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Fallidos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['failed'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="mb-6 bg-white shadow rounded-lg p-4">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                   placeholder="Orden, transacción, usuario..." 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
            <select id="type" name="type" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Todos los tipos</option>
                <option value="publication" {{ request('type') == 'publication' ? 'selected' : '' }}>Publicación</option>
                <option value="commission_requested" {{ request('type') == 'commission_requested' ? 'selected' : '' }}>Comisión Solicitada</option>
                <option value="commission_offered" {{ request('type') == 'commission_offered' ? 'selected' : '' }}>Comisión Ofrecida</option>
            </select>
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
            <select id="status" name="status" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        
        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
            <select id="user_id" name="user_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Todos los usuarios</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-filter mr-2"></i> Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Tabla de pagos -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Lista de Pagos
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ $payments->total() }} pagos encontrados - 
                    Total recaudado: ${{ number_format($stats['total_amount'], 0, ',', '.') }}
                </p>
            </div>
            <div class="text-sm text-gray-500">
                Hoy: {{ $stats['today_count'] }} pagos - ${{ number_format($stats['today_amount'], 0, ',', '.') }}
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pago
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Usuario
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Monto
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
                @forelse ($payments as $payment)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $payment->id }}</div>
                        <div class="text-sm text-gray-500">{{ $payment->buy_order }}</div>
                        @if($payment->transaction_id)
                        <div class="text-xs text-gray-400">{{ Str::limit($payment->transaction_id, 20) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="h-8 w-8 rounded-full mr-2" src="{{ $payment->user->profile_photo_url }}" alt="{{ $payment->user->name }}">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($payment->type == 'publication') bg-blue-100 text-blue-800
                            @elseif($payment->type == 'commission_requested') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $payment->formatted_type }}
                        </span>
                        @if($payment->product)
                        <div class="text-xs text-gray-500 mt-1">
                            Producto: {{ Str::limit($payment->product->title, 25) }}
                        </div>
                        @endif
                        @if($payment->offer)
                        <div class="text-xs text-gray-500 mt-1">
                            Oferta: #{{ $payment->offer->id }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($payment->status == 'approved') bg-green-100 text-green-800
                            @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status == 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $payment->formatted_status }}
                        </span>
                        @if($payment->authorization_code)
                        <div class="text-xs text-gray-500 mt-1">
                            Auth: {{ $payment->authorization_code }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $payment->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs">{{ $payment->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.payments.show', $payment) }}" 
                           class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150"
                           title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fas fa-credit-card text-gray-300 text-4xl mb-2"></i>
                            <p class="text-gray-500">No se encontraron pagos</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection