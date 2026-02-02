{{-- resources/views/admin/layouts/sidebar.blade.php --}}
<div class="flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white">
    <!-- Logo mejorado -->
    <div class="flex items-center justify-between h-16 flex-shrink-0 px-4 border-b border-gray-200">
        <div class="flex items-center min-w-0">
            <!-- Logo expandido -->
            <div x-show="sidebarOpen" x-cloak class="flex items-center min-w-0">
                <i class="fas fa-exchange-alt text-orange-500 text-xl"></i>
                <span class="ml-2 text-lg font-bold text-gray-900 truncate">Admin Panel</span>
            </div>
            <!-- Logo contraído -->
            <div x-show="!sidebarOpen" x-cloak class="flex items-center justify-center w-full">
                <i class="fas fa-exchange-alt text-orange-500 text-xl"></i>
            </div>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="hidden md:block text-gray-400 hover:text-gray-500 transition-colors duration-200">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        @php
            $currentRoute = request()->route()->getName();
        @endphp
        
        <a href="{{ route('admin.dashboard') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.dashboard')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-tachometer-alt mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.dashboard')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.users.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.users')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-users mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.users')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Usuarios</span>
        </a>
        
        <a href="{{ route('admin.products.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.products')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-box mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.products')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Productos</span>
        </a>
        
        <a href="{{ route('admin.categories.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.categories')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-tags mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.categories')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Categorías</span>
        </a>
        
        <a href="{{ route('admin.offers.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.offers')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-exchange-alt mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.offers')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Ofertas</span>
        </a>
        
        <a href="{{ route('admin.chats.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.chats')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-comments mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.chats')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Chats</span>
        </a>
        
        <a href="{{ route('admin.questions.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.questions')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-question-circle mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.questions')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Preguntas</span>
        </a>
        
        <a href="{{ route('admin.reviews.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.reviews')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-star mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.reviews')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Reseñas</span>
        </a>
        
        <a href="{{ route('admin.payments.index') }}" 
           class="@if(str_starts_with($currentRoute, 'admin.payments')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-credit-card mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(str_starts_with($currentRoute, 'admin.payments')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Pagos</span>
        </a>
        
        <a href="{{ route('admin.reports.index') }}" 
           class="@if(request()->routeIs('admin.reports.*')) active-nav @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
            <i class="fas fa-chart-bar mr-3 flex-shrink-0 text-gray-400 group-hover:text-orange-500 @if(request()->routeIs('admin.reports.*')) text-orange-500 @endif"></i>
            <span x-show="sidebarOpen" x-cloak>Reportes</span>
        </a>
    </nav>

    <!-- User profile -->
    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
        <div class="flex items-center w-full">
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
            </div>
            <div x-show="sidebarOpen" x-cloak class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-700 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs font-medium text-gray-500 truncate">{{ auth()->user()->email }}</p>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="text-xs text-gray-500 hover:text-orange-600 flex items-center mt-1 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>