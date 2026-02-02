{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Plataforma de Permutas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: all 0.3s ease-in-out; }
        .active-nav { 
            background-color: rgb(255 237 213); /* orange-50 */
            color: rgb(194 65 12); /* orange-700 */
        }
        .active-nav:hover { 
            background-color: rgb(254 215 170); /* orange-200 */
            color: rgb(154 52 18); /* orange-800 */
        }
        
        /* Estilo para mejorar el logo en modo contraído */
        .logo-container {
            transition: all 0.3s ease;
        }
        
        /* Mejoras para la navegación en modo contraído */
        .nav-item-contracted {
            justify-content: center;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: window.innerWidth >= 768, mobileSidebarOpen: false }">
    <!-- Mobile sidebar backdrop -->
    <div x-show="mobileSidebarOpen" x-cloak 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex z-40 md:hidden" 
         @click="mobileSidebarOpen = false">
        <div class="fixed inset-0">
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-col md:fixed md:inset-y-0 sidebar-transition"
         :class="sidebarOpen ? 'md:w-64' : 'md:w-20'">
        @include('admin.layouts.sidebar')
    </div>

    <!-- Mobile sidebar -->
    <div x-show="mobileSidebarOpen" x-cloak
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="md:hidden fixed inset-y-0 left-0 flex w-64 z-50 sidebar-transition">
        @include('admin.layouts.sidebar')
    </div>

    <!-- Main content -->
    <div class="md:pl-64 flex flex-col flex-1" :class="!sidebarOpen && 'md:pl-20'">
        @include('admin.layouts.header')
        
        <main class="flex-1 pb-8">
            <!-- Page header -->
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8">
                    <div class="py-6 md:flex md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <!-- Breadcrumb -->
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol role="list" class="flex items-center space-x-4">
                                    <li>
                                        <div class="flex">
                                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-orange-700 transition-colors duration-200">
                                                <i class="fas fa-home mr-2"></i>Dashboard
                                            </a>
                                        </div>
                                    </li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                                @yield('title', 'Dashboard')
                            </h1>
                            @hasSection('subtitle')
                            <p class="mt-1 text-sm text-gray-500">
                                @yield('subtitle')
                            </p>
                            @endif
                        </div>
                        <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
                            @yield('actions')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="mt-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                    @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="rounded-md bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed top-4 right-4 p-4 rounded-md text-white z-50"></div>

    <script>
        // Toast notification system
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.className = `fixed top-4 right-4 p-4 rounded-md text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 4000);
        }

        // Auto-show toast from session
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        // Confirm dialogs
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
    </script>
</body>
</html>