{{-- resources/views/admin/reports/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Reportes y Métricas')
@section('subtitle', 'Estadísticas y análisis de la plataforma')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Reportes</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    <form method="GET" action="{{ route('admin.reports.export') }}" class="inline">
        <input type="hidden" name="date_range" value="{{ $dateRange }}">
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-download mr-2"></i> Exportar CSV
        </button>
    </form>
</div>
@endsection

@section('content')
<!-- Filtros -->
<div class="mb-6 bg-white shadow rounded-lg p-4">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-end space-x-4">
        <div>
            <label for="date_range" class="block text-sm font-medium text-gray-700">Período</label>
            <select id="date_range" name="date_range" onchange="this.form.submit()" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="7days" {{ $dateRange == '7days' ? 'selected' : '' }}>Últimos 7 días</option>
                <option value="30days" {{ $dateRange == '30days' ? 'selected' : '' }}>Últimos 30 días</option>
                <option value="90days" {{ $dateRange == '90days' ? 'selected' : '' }}>Últimos 90 días</option>
                <option value="1year" {{ $dateRange == '1year' ? 'selected' : '' }}>Último año</option>
            </select>
        </div>
    </form>
</div>

<!-- Estadísticas principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Ingresos Totales</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pagos Realizados</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_payments'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Nuevos Usuarios</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-exchange-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Ofertas Realizadas</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_offers'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Ingresos por día -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Ingresos por Día</h3>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Pagos por tipo -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Distribución de Pagos por Tipo</h3>
        <div class="h-64">
            <canvas id="paymentsTypeChart"></canvas>
        </div>
    </div>

    <!-- Usuarios registrados -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Usuarios Registrados por Día</h3>
        <div class="h-64">
            <canvas id="usersChart"></canvas>
        </div>
    </div>

    <!-- Productos por categoría -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Productos por Categoría</h3>
        <div class="h-64">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>
</div>

<!-- Top datos -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Top usuarios -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Top 10 Usuarios</h3>
            <p class="mt-1 text-sm text-gray-500">Usuarios con mayor gasto en la plataforma</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="space-y-4">
                @foreach($topData['top_users'] as $user)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img class="h-8 w-8 rounded-full mr-3" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->payments_count }} pagos</p>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-900">
                        ${{ number_format($user->payments_sum_amount, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top productos -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Top 10 Productos</h3>
            <p class="mt-1 text-sm text-gray-500">Productos con más ofertas recibidas</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="space-y-4">
                @foreach($topData['top_products'] as $product)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img class="h-8 w-8 rounded-md object-cover mr-3" src="{{ $product->first_image_url }}" alt="{{ $product->title }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($product->title, 25) }}</p>
                            <p class="text-xs text-gray-500">{{ $product->offers_count }} ofertas</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top categorías -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Top 10 Categorías</h3>
            <p class="mt-1 text-sm text-gray-500">Categorías con más productos</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="space-y-4">
                @foreach($topData['top_categories'] as $category)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($category->image)
                        <img class="h-8 w-8 rounded-md object-cover mr-3" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                            <p class="text-xs text-gray-500">{{ $category->products_count }} productos</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Scripts para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ingresos por día
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: [{!! $charts['revenueByDay']->pluck('date')->map(function($date) { return "'" . Carbon\Carbon::parse($date)->format('d/m') . "'"; })->join(',') !!}],
            datasets: [{
                label: 'Ingresos',
                data: [{{ $charts['revenueByDay']->pluck('total')->join(',') }}],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Pagos por tipo
    const paymentsTypeCtx = document.getElementById('paymentsTypeChart').getContext('2d');
    const paymentsTypeChart = new Chart(paymentsTypeCtx, {
        type: 'doughnut',
        data: {
            labels: [{!! $charts['paymentsByType']->pluck('type')->map(function($type) { 
                $types = [
                    'publication' => 'Publicación',
                    'commission_requested' => 'Comisión Solicitada',
                    'commission_offered' => 'Comisión Ofrecida'
                ];
                return "'" . ($types[$type] ?? $type) . "'"; 
            })->join(',') !!}],
            datasets: [{
                data: [{{ $charts['paymentsByType']->pluck('count')->join(',') }}],
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Usuarios registrados
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: [{!! $charts['usersByDay']->pluck('date')->map(function($date) { return "'" . Carbon\Carbon::parse($date)->format('d/m') . "'"; })->join(',') !!}],
            datasets: [{
                label: 'Usuarios',
                data: [{{ $charts['usersByDay']->pluck('count')->join(',') }}],
                backgroundColor: '#8B5CF6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Productos por categoría
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    const categoriesChart = new Chart(categoriesCtx, {
        type: 'pie',
        data: {
            labels: [{!! $charts['productsByCategory']->pluck('name')->map(function($name) { return "'" . $name . "'"; })->join(',') !!}],
            datasets: [{
                data: [{{ $charts['productsByCategory']->pluck('count')->join(',') }}],
                backgroundColor: [
                    '#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6',
                    '#EC4899', '#6B7280', '#84CC16', '#F97316', '#06B6D4'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection