{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <!-- Stats Grid Mejorado - Solo 4 tarjetas principales -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Usuarios -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-orange-500 hover:shadow-md transition-all duration-200 group">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors duration-200">
                            <i class="fas fa-users text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500">Total Usuarios</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-500">
                    <i class="fas fa-user-plus mr-1"></i>
                    <span>Registrados en la plataforma</span>
                </div>
            </div>
        </div>

        <!-- Total Productos -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500 hover:shadow-md transition-all duration-200 group">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors duration-200">
                            <i class="fas fa-box text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500">Productos</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-500">
                    <i class="fas fa-cube mr-1"></i>
                    <span>Productos publicados</span>
                </div>
            </div>
        </div>

        <!-- Ofertas Pendientes -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-yellow-500 hover:shadow-md transition-all duration-200 group">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors duration-200">
                            <i class="fas fa-exchange-alt text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500">Ofertas</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $stats['pending_offers'] }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    <span>Pendientes de revisión</span>
                </div>
            </div>
        </div>

        <!-- Permutas Completadas -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500 hover:shadow-md transition-all duration-200 group">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-200">
                            <i class="fas fa-handshake text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500">Permutas</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $stats['completed_trades'] }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-500">
                    <i class="fas fa-check-circle mr-1"></i>
                    <span>Intercambios exitosos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda Fila - Métricas Secundarias -->
    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
        <!-- Chats Activos -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:border-blue-300 transition-all duration-200">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-comments text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500">Chats Activos</dt>
                            <dd class="text-xl font-bold text-gray-900">{{ $stats['open_chats'] }}</dd>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500">Conversaciones</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reseñas Pendientes -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:border-purple-300 transition-all duration-200">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500">Reseñas Pendientes</dt>
                            <dd class="text-xl font-bold text-gray-900">{{ $stats['pending_reviews'] }}</dd>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500">Por moderar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Acciones Rápidas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Acción: Gestionar Usuarios -->
        <a href="{{ route('admin.users.index') }}" class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:border-orange-300 hover:shadow-md transition-all duration-200 group">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors duration-200">
                            <i class="fas fa-users text-orange-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-orange-600 transition-colors duration-200">Usuarios</h3>
                        <p class="text-sm text-gray-500">Gestionar usuarios</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Acción: Gestionar Productos -->
        <a href="{{ route('admin.products.index') }}" class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200 group">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors duration-200">
                            <i class="fas fa-box text-green-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-green-600 transition-colors duration-200">Productos</h3>
                        <p class="text-sm text-gray-500">Administrar productos</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Acción: Gestionar Ofertas -->
        <a href="{{ route('admin.offers.index') }}" class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:border-yellow-300 hover:shadow-md transition-all duration-200 group">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors duration-200">
                            <i class="fas fa-exchange-alt text-yellow-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-yellow-600 transition-colors duration-200">Ofertas</h3>
                        <p class="text-sm text-gray-500">Revisar ofertas</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Acción: Gestionar Chats -->
        <a href="{{ route('admin.chats.index') }}" class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 group">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-200">
                            <i class="fas fa-comments text-blue-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200">Chats</h3>
                        <p class="text-sm text-gray-500">Moderar conversaciones</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Actividad Reciente Mejorada -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Actividad Reciente -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-clock text-orange-500 mr-2"></i>
                    Actividad Reciente
                </h3>
                <span class="text-sm text-gray-500">{{ $recentActivities->count() }} actividades</span>
            </div>
            <div class="overflow-hidden">
                <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto activity-list">
                    @forelse($recentActivities as $activity)
                    <li class="hover:bg-gray-50 transition-colors duration-150">
                        <div class="px-6 py-4 flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center">
                                    <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }}-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity['title'] }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $activity['description'] }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i>{{ $activity['time']->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li>
                        <div class="px-6 py-8 text-center">
                            <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                            <p class="text-gray-500">No hay actividad reciente</p>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Estado del Sistema -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <i class="fas fa-heartbeat text-green-500 mr-2"></i>
                    Estado del Sistema
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Estado de la plataforma -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">Plataforma</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Operativa
                        </span>
                    </div>

                    <!-- Base de datos -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">Base de datos</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Conectada
                        </span>
                    </div>

                    <!-- Servicio de correo -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">Servicio de correo</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Activo
                        </span>
                    </div>

                    <!-- Tiempo de actividad -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">Tiempo de actividad</span>
                        </div>
                        <span class="text-sm text-gray-500">99.9%</span>
                    </div>

                    <!-- Última actualización -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">Última actualización</span>
                        </div>
                        <span class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <!-- Separador -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Acciones Rápidas</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-chart-bar mr-1 text-gray-400"></i>
                            Reportes
                        </a>
                        <a href="{{ route('admin.questions.index') }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-question-circle mr-1 text-gray-400"></i>
                            Preguntas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animaciones suaves para las tarjetas */
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
    }
    
    /* Scroll personalizado para la actividad reciente */
    .activity-list::-webkit-scrollbar {
        width: 4px;
    }
    
    .activity-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .activity-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .activity-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush