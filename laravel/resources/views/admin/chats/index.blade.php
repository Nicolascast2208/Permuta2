{{-- resources/views/admin/chats/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Gestión de Chats')
@section('subtitle', 'Administrar conversaciones entre usuarios')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Chats</span>
    </div>
</li>
@endsection

@section('content')
<!-- Filtros -->
<div class="mb-6 bg-white shadow rounded-lg p-4">
    <form method="GET" action="{{ route('admin.chats.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                   placeholder="Usuario, email..." 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
            <select id="status" name="status" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Todos los estados</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Abiertos</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Cerrados</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-filter mr-2"></i> Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Tabla de chats -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Lista de Chats
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ $chats->total() }} chats encontrados
        </p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Chat
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Usuarios
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Oferta
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mensajes
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
                @forelse ($chats as $chat)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $chat->id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <strong>Usuario 1:</strong> {{ $chat->user1->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            <strong>Usuario 2:</strong> {{ $chat->user2->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($chat->offer)
                        <a href="{{ route('admin.offers.show', $chat->offer) }}" class="text-indigo-600 hover:text-indigo-900">
                            Oferta #{{ $chat->offer->id }}
                        </a>
                        @else
                        <span class="text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $chat->messages->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chat->is_closed ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $chat->is_closed ? 'Cerrado' : 'Abierto' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $chat->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.chats.show', $chat) }}" 
                               class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150"
                               title="Ver chat">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$chat->is_closed)
                            <form action="{{ route('admin.chats.close', $chat) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-150"
                                        title="Cerrar chat">
                                    <i class="fas fa-lock"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('admin.chats.destroy', $chat) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmAction('¿Estás seguro de eliminar este chat?', () => this.form.submit())"
                                        class="text-red-600 hover:text-red-900 transition-colors duration-150"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fas fa-comments text-gray-300 text-4xl mb-2"></i>
                            <p class="text-gray-500">No se encontraron chats</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $chats->links() }}
    </div>
</div>
@endsection