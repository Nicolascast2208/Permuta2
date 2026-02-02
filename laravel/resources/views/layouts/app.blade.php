<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permuta2 - @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/custom.css'])
    @livewireStyles
    @stack('styles')
    <style>

    </style>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KTQNJM78');</script>
<!-- End Google Tag Manager -->
</head>
<body class="bg-white">
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KTQNJM78"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
        <!-- Header -->
       
        <header class="fondo-nuevo top-0 z-50 {{ request()->is('dashboard*') ? '' : 'sticky' }}">
           <!-- 1️⃣ BARRA SUPERIOR -->
        <div class="hidden md:block huincha">    
    <div class="flex justify-between items-center text-sm px-6 py-2 max-w-7xl mx-auto">
        <div class="flex items-center space-x-2">
   
        </div>
        <div class="flex space-x-2">
                      @auth
                            <div class="hidden md:flex justify-center md:justify-end flex-wrap space-x-2 text-gray-700 text-sm mt-4 md:mt-0 items-end">
                     <div class="relative">
                           <button id="notification-button" class="flex flex-col items-center focus:outline-none text-gray-800 font-regular w-6 h-6">
                          <div class="relative">
                             <i class="far fa-bell fa-lg mb-3 text-black"></i>
                                  @php
                                    $unreadCount = auth()->user()->notifications()->where('read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                            <span id="notification-badge" class="absolute top-2 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                    {{ $unreadCount }}
                          @endif
                     </div>
                         
                          </button>
<!-- Dropdown Notificaciones -->
<div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-96 fondo-gris border rounded-2xl shadow-xl z-50 overflow-hidden">
    @php
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();
    @endphp
    
    @if($notifications->count() > 0)
        <div class="p-4 border-b">
            <h3 class="text-lg font-bold text-gray-800 text-center">Notificaciones</h3>
        </div>
        
        <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto" id="notification-list">
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
    
    case 'payment_required':
    $redirectUrl = route('product_question') . '?status=waiting_payment';
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
                
                <a href="{{ $redirectUrl }}" 
                   class="block notification-item hover:no-underline" 
                   data-notification-id="{{ $notification->id }}"
                   onclick="markNotificationAsRead(event, {{ $notification->id }})">
                    <li class="flex items-center space-x-4 p-4 hover:bg-gray-50 transition cursor-pointer {{ !$notification->read ? 'bg-gray-100 unread-notification' : 'bg-white' }}">
                        <div class="text-blue-600 flex-shrink-0">
                            @switch($notification->type)
                                @case('product_created')
                                    <i class="fas fa-box-open text-xl"></i>
                                    @break
                                @case('offer_received')
                                    <i class="fas fa-envelope-open-text text-xl"></i>
                                    @break
                                @case('trade_accepted')
                                    <i class="fas fa-check-circle text-xl"></i>
                                    @break
                                    @case('product_question')
                                    <i class="fas fa-question-circle text-xl"></i>
                                    @break
                                @case('question_answered')
                                    <i class="fas fa-reply text-xl"></i>
                                    @break
                                @default
                                    <i class="fas fa-bell text-xl"></i>
                            @endswitch
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 leading-snug">
                                {{ $notification->message ?? $notification->data['message'] ?? 'Nueva notificación' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                </a>
            @endforeach
        </ul>
        
        <div class="text-center p-3 border-t bg-gray-50">
            <a href="{{ route('notifications.index') }}" class="text-blue-600 text-sm font-medium hover:underline">
                Ver todas
            </a>
        </div>
    @else
        <div class="p-6 text-center text-gray-500 text-sm">
            No tienes notificaciones
        </div>
    @endif
</div>
</div>
                                
                                <!-- Dropdown Perfil -->
                                <div class="relative" id="profile-dropdown-container">
                       <button id="profile-button" class="flex items-center space-x-2 hover:bg-[#ffce00] focus:outline-none px-2 py-1 rounded-lg transition">
    <div class="w-6 h-6 rounded-full overflow-hidden border-2 border-green-700">
        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->alias }}" class="w-full h-full object-cover">
    </div>
    <span class="font-medium text-gray-800 capitalize">Hola, {{ auth()->user()->name }}</span>
</button>
                                    <div id="profile-dropdown" class="profile-dropdown absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border z-50">
                                        <ul class="py-2 text-sm text-black">
                                            <li>
                                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 hover:bg-[#ffce00]">
                                                    <i class="fas fa-user w-5 mr-2"></i> Mi Perfil
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('products.createx') }}" class="flex items-center px-4 py-2 hover:bg-[#ffce00]">
                                                    <i class="fas fa-plus w-5 mr-2"></i> Publicar Producto
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('dashboard.my-products') }}" class="flex items-center px-4 py-2 hover:bg-[#ffce00]">
                                                    <i class="fas fa-box w-5 mr-2"></i> Mis Productos
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('dashboard.trades') }}" class="flex items-center px-4 py-2 hover:bg-[#ffce00]">
                                                    <i class="fas fa-exchange-alt w-5 mr-2"></i> Mis Permutas
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('dashboard.questions') }}" class="flex items-center px-4 py-2 hover:bg-[#ffce00]">
                                                    <i class="fas fa-message w-5 mr-2"></i> Preguntas
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('dashboard.received-offers') }}" class="flex items-center px-4 py-2 hover:bg-[#ffce00]">
                                                    <i class="fas fa-envelope-open-text w-5 mr-2"></i> Ofertas Recibidas
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('dashboard.sent-offers') }}" class="flex items-center px-4 py-2 hover:bg-[#ffce00]">
                                                    <i class="fas fa-envelope w-5 mr-2"></i> Ofertas Enviadas
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="border-t">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-2 flex items-center  text-red-400">
                                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i> Cerrar sesión
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                            @else
                                  <div class="flex items-center justify-center text-gray-800 font-semibold">
    <!-- Ingresar -->
    <a href="{{ route('login') }}" class="flex items-center hover:underline mr-3">
        <i class="far fa-user mr-2"></i>
        INGRESAR
    </a>

    <!-- Línea centrada -->
    <span class="block h-5 w-px bg-black mx-3"></span>

    <!-- Crear cuenta -->
    <a href="{{ route('register') }}" class="hover:underline ml-3">
        CREAR CUENTA
    </a>
</div>
                            @endauth
        </div>
    </div>
       </div>
<div class="max-w-7xl mx-auto px-6 py-2">
    <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-y-4">
        <!-- LOGO + MENU BUTTON (mobile) -->
        <div class="flex items-center justify-between md:justify-start col-span-4 md:col-span-3">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('storage/logo-blanco.png') }}" alt="Permuta2 Logo" class="h-20 sm:h-32 md:h-25 w-auto">
            </a>
                 <a href="{{ route('products.createx') }}"
       class="block sm:hidden fondo-naranjo text-white font-semibold 
          px-4 py-2 rounded-full shadow hover:bg-red-700 
          transition items-center space-x-2 w-auto mx-auto">
       <!-- <i class="fas fa-plus "></i>-->
        <span>PUBLICAR</span>
    </a>
            <!-- burger mobile -->
            <button id="mobile-menu-button" class="mobile-only text-white text-2xl md:hidden focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- BUSCADOR MÁS ANCHO -->
        <div class="md:col-span-7 w-full">
            <!-- botones alineados a la izquierda y juntos -->
               <div class="flex justify-start">

                    <button type="button" id="search-type-want" class="px-6 py-1 fondo-naranjo text-white font-semibold text-sm  rounded-tl-xl transition-colors search-tab active">QUE BUSCO</button>
                    
                   
                    <button type="button" id="search-type-have" class="px-6 py-1 buscador text-gray-700 font-semibold text-sm rounded-tr-xl transition-colors search-tab">QUE OFREZCO</button>
               
                </div>

            <!-- formulario más ancho -->
            <form action="{{ route('products.index') }}" method="GET" class="relative w-full">
                <input type="hidden" name="type" id="search-type" value="want">
                <input type="text" name="q" placeholder="Buscar Artículos, Servicios..." class="w-full border rounded-full rounded-tl-none bg-white pl-4 pr-10 py-2 text-sm shadow-sm focus:outline-none">
                <button type="submit" class="absolute right-3 top-2 texto-naranjo">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- PUBLICAR centrado y alineado con el input -->
<div class="md:col-span-2 flex items-center justify-center w-full h-full md:pt-7">
    <a href="{{ route('products.createx') }}"
       class="hidden md:flex fondo-naranjo text-white font-semibold 
          px-4 py-2 md:px-6 md:py-2 
          rounded-full shadow hover:bg-red-700 
          transition items-center space-x-2 w-auto mx-auto">
       <!-- <i class="fas fa-plus "></i>-->
        <span>PUBLICAR</span>
    </a>
</div>
          </div>
</div>
    </div>

     <!-- MENÚ INFERIOR / CATEGORÍAS -->
    <nav class="hidden md:block max-w-7xl mx-auto px-6 pb-3 ">
        <div class="flex justify-center">
            <div class="flex items-center space-x-8 text-sm font-medium text-gray-800">

                <!-- Mega menu container: ahora controlado por JS y hover -->
                <div class="mega-menu-container relative" id="mega-menu-container">
                    <button id="mega-menu-button" class="hover:underline font-medium flex items-center">
                        CATEGORÍAS
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>

                    <!-- mega menu: inicialmente hidden (se muestra con .active) -->
                    <div class="mega-menu absolute left-1/2 transform -translate-x-1/2 mt-2 w-96 bg-white border border-gray-200 rounded-xl shadow-xl z-50" id="mega-menu">
                        <div class="p-4">
                            <a href="{{ route('categories.index') }}">
                                <h3 class="text-lg font-bold texto-naranjo mb-3 border-b pb-2">Categorías Principales</h3>
                            </a>
                            <div class="space-y-2 max-h-80 overflow-y-auto">
                                @php
                                    $categories = \App\Models\Category::mainCategories()->ordered()->get();
                                @endphp

                                @foreach($categories as $category)
                                    <a href="{{ route('categories.show', $category->slug) }}" class="category-header flex items-center p-2 text-gray-700 hover:text-orange-500">
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-6 h-6 mr-3 object-cover rounded">
                                        @else
                                            <div class="w-6 h-6 mr-3 bg-orange-100 rounded flex items-center justify-center">
                                                <i class="fas fa-folder text-orange-500 text-xs"></i>
                                            </div>
                                        @endif
                                        <span class="text-sm font-medium">{{ $category->name }}</span>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200 text-center">
                                <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Ver todas las categorías <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

             
                <a href="{{ route('users.index') }}" class="hover:underline font-medium">PERMUTADORES</a>
          
                <a href="{{ route('como.permutar')}}" class="hover:underline font-medium">¿CÓMO PERMUTAR?</a>

                  <a href="{{ route('faq')}}" class="hover:underline font-medium">AYUDA</a>
            </div>
        </div>
    </nav>
        </header>
        
        <!-- Menú móvil -->
        <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>
        <div class="mobile-menu" id="mobile-menu">
            <div class="mobile-menu-header">
                <h3 class="text-lg font-bold">Menú</h3>
                <button id="mobile-menu-close" class="text-gray-500 text-xl focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mobile-menu-content">
                @auth
                <!-- Información del usuario -->
                <div class="mobile-user-info">
                    <div class="mobile-user-avatar">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->alias }}" class="w-full h-full object-cover">
                    </div>
                    <div class="mobile-user-details">
                        <div class="mobile-user-name">{{ auth()->user()->name }}</div>
                        <div class="mobile-user-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                
                <!-- Sección de navegación principal -->
                <div class="mobile-menu-section">
                    <div class="mobile-menu-section-title">Cuenta</div>
                    <a href="{{ route('dashboard') }}" class="mobile-menu-link">
                        <i class="fas fa-user w-5 mr-2"></i> Mi Perfil
                    </a>
                    <a href="{{ route('products.createx') }}" class="mobile-menu-link">
                        <i class="fas fa-plus w-5 mr-2"></i> Publicar Producto
                    </a>
                    <a href="{{ route('dashboard.my-products') }}" class="mobile-menu-link">
                        <i class="fas fa-box w-5 mr-2"></i> Mis Productos
                    </a>
                    <a href="{{ route('dashboard.trades') }}" class="mobile-menu-link">
                        <i class="fas fa-exchange-alt w-5 mr-2"></i> Mis Permutas
                    </a>
                    <a href="{{ route('dashboard.questions') }}" class="mobile-menu-link">
                        <i class="fas fa-message w-5 mr-2"></i> Preguntas
                    </a>
                    <a href="{{ route('dashboard.received-offers') }}" class="mobile-menu-link">
                        <i class="fas fa-envelope-open-text w-5 mr-2"></i> Ofertas Recibidas
                    </a>
                    <a href="{{ route('dashboard.sent-offers') }}" class="mobile-menu-link">
                        <i class="fas fa-envelope w-5 mr-2"></i> Ofertas Enviadas
                    </a>
                </div>
                
                <!-- Notificaciones -->
                <div class="mobile-menu-section">
                    <div class="mobile-menu-section-title">Notificaciones</div>
                    <a href="{{ route('notifications.index') }}" class="mobile-menu-link">
                        <i class="fas fa-bell w-5 mr-2"></i> Ver Notificaciones
                        @php
                            $unreadCount = auth()->user()->notifications()->where('read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full ml-2">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>
                @else
                <!-- Menú para usuarios no autenticados -->
                <div class="mobile-menu-section">
                    <div class="mobile-menu-section-title">Cuenta</div>
                    <a href="{{ route('login') }}" class="mobile-menu-link">
                        <i class="fas fa-sign-in-alt w-5 mr-2"></i> Ingresar
                    </a>
                    <a href="{{ route('register') }}" class="mobile-menu-link">
                        <i class="fas fa-user-plus w-5 mr-2"></i> Crear Cuenta
                    </a>
                </div>
                @endauth
                
                <!-- Enlaces generales -->
                <div class="mobile-menu-section">
                    <div class="mobile-menu-section-title">Navegación</div>
                    <a href="{{ route('categories.index') }}" class="mobile-menu-link">
                        <i class="fas fa-list w-5 mr-2"></i> Categorías
                    </a>
           
                    <a href="{{ route('users.index') }}" class="mobile-menu-link">
                        <i class="fas fa-users w-5 mr-2"></i> Permutadores
                    </a>
                    <a href="{{ route('como.permutar') }}" class="mobile-menu-link">
                        <i class="fas fa-question-circle w-5 mr-2"></i> ¿Cómo permutar?
                    </a>
                </div>
                
                <!-- Enlaces de información -->
                <div class="mobile-menu-section">
                    <div class="mobile-menu-section-title">Información</div>
                    <a href="{{ route('nosotros') }}" class="mobile-menu-link">
                        <i class="fas fa-info-circle w-5 mr-2"></i> Nosotros
                    </a>
                    <a href="{{ route('privacy') }}" class="mobile-menu-link">
                        <i class="fas fa-shield-alt w-5 mr-2"></i> Políticas de privacidad
                    </a>
                    <a href="{{ route('terms') }}" class="mobile-menu-link">
                        <i class="fas fa-file-contract w-5 mr-2"></i> Términos y condiciones
                    </a>
                </div>
                
                @auth
                <!-- Cerrar sesión -->
                <div class="mobile-menu-section">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-menu-link w-full text-left">
                            <i class="fas fa-sign-out-alt w-5 mr-2"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
        
        <!-- Main Content -->
@if(request()->is('dashboard*') || request()->is('chat*')|| request()->is('notifications*') )
<!-- Contenedor flex mejorado para sidebar sticky -->
<div class="flex items-start relative ">

    <!-- Sidebar sticky mejorado -->
    <aside id="sidebar" class="hidden md:block sidebar fondo-menu-dashboard text-gray-800 rounded-r-lg mt-6" aria-label="Menú lateral">
        <div class="flex flex-col py-4 px-2 h-full">
            <!-- Botón de expandir/contraer -->
            <div class="flex justify-end px-4">
                <button id="sidebar-toggle" class="text-gray-800 hover:text-gray-900 focus:outline-none text-xl" aria-label="Expandir menú">
                    <i id="sidebar-icon" class="fas fa-chevron-left"></i>
                </button>
            </div>

            <!-- Elementos del menú -->
            <nav class="mt-4 flex-1">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="rounded-xl flex items-center px-4 py-3 hover:bg-yellow-500 transition-colors {{ request()->is('dashboard') ? 'bg-yellow-500 font-semibold' : '' }}">
                            <div class="sidebar-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="sidebar-item-text ml-3">Mi Perfil</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.createx') }}" class="rounded-xl flex items-center px-4 py-3 hover:bg-yellow-500 transition-colors {{ request()->is('crear-producto/*') ? 'bg-yellow-500 font-semibold' : '' }}">
                            <div class="sidebar-icon">
                              <i class="fas fa-plus"></i>
                            </div>
                            <span class="sidebar-item-text ml-3">Publicar Producto</span>
                        </a>
                    </li>
    
                    <li>
                        <a href="{{ route('dashboard.my-products') }}" class="rounded-xl flex items-center px-4 py-3 hover:bg-yellow-500 transition-colors {{ request()->is('dashboard/my-products*') ? 'bg-yellow-500 font-semibold' : '' }}">
                            <div class="sidebar-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <span class="sidebar-item-text ml-3">Mis Productos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.trades') }}" class="rounded-xl flex items-center px-4 py-3 hover:bg-yellow-500 transition-colors {{ request()->is('dashboard/permutas*') ? 'bg-yellow-500 font-semibold' : '' }}">
                            <div class="sidebar-icon">
                              <i class="fas fa-exchange-alt"></i>
                            </div>
                            <span class="sidebar-item-text ml-3">Mis Permutas</span>
                        </a>
                    </li>
                                    
<li>
    <a href="{{ route('dashboard.questions') }}" class="rounded-xl flex items-center px-4 py-3 hover:bg-yellow-500 transition-colors {{ request()->is('dashboard/preguntas*') ? 'bg-yellow-500 font-semibold' : '' }}">
        <div class="sidebar-icon relative">
            <i class="fas fa-message"></i>
            @php
                $unreadQuestionsCount = Auth::check() ? 
                    App\Models\Question::whereHas('product', function($query) {
                        $query->where('user_id', Auth::id());
                    })->where('read_by_seller', false)->count() : 0;
            @endphp
            @if($unreadQuestionsCount > 0)
                <span class="notification-bubble-sidebar absolute -top-1 -right-1 w-3 h-3 bg-red-600 rounded-full notification-bubble"></span>
            @endif
        </div>
        <span class="sidebar-item-text ml-3">Preguntas</span>
        <!-- Esta es la versión expandida que se mostrará cuando el sidebar esté abierto -->
        @if($unreadQuestionsCount > 0)
            <span class="sidebar-expanded-bubble ml-auto inline-flex items-center justify-center min-w-[1.5rem] h-6 text-xs font-bold leading-none text-white bg-red-600 rounded-full notification-bubble">
                {{ $unreadQuestionsCount }}
            </span>
        @else
            <div class="w-6 sidebar-expanded-bubble"></div>
        @endif
    </a>
</li>

<li>
    <a href="{{ route('dashboard.received-offers') }}" class="rounded-xl flex items-center px-4 py-3 hover:bg-yellow-500 transition-colors {{ request()->is('dashboard/ofertas-recibidas*') ? 'bg-yellow-500 font-semibold' : '' }}">
        <div class="sidebar-icon relative">
            <i class="fas fa-envelope-open-text"></i>
            @php
                $unreadOffersCount = Auth::check() ? Auth::user()->unreadReceivedOffers()->count() : 0;
            @endphp
            @if($unreadOffersCount > 0)
                <span class="notification-bubble-sidebar absolute -top-1 -right-1 w-3 h-3 bg-red-600 rounded-full notification-bubble"></span>
            @endif
        </div>
        <span class="sidebar-item-text ml-3">Ofertas Recibidas</span>
        <!-- Esta es la versión expandida que se mostrará cuando el sidebar esté abierto -->
        @if($unreadOffersCount > 0)
            <span class="sidebar-expanded-bubble ml-auto inline-flex items-center justify-center min-w-[1.5rem] h-6 text-xs font-bold leading-none text-white bg-red-600 rounded-full notification-bubble">
                {{ $unreadOffersCount }}
            </span>
        @else
            <div class="w-6 sidebar-expanded-bubble"></div>
        @endif
    </a>
</li>
                    <li>
                        <a href="{{ route('dashboard.sent-offers') }}" class="rounded-xl flex items-center px-4 py-3 hover:bg-yellow-500 transition-colors {{ request()->is('dashboard/ofertas-enviadas') ? 'bg-yellow-500 font-semibold' : '' }}">
                            <div class="sidebar-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span class="sidebar-item-text ml-3">Ofertas Enviadas</span>
                        </a>
                    </li>

                    <div class="sidebar-divider"></div>
                </ul>
            </nav>

            <!-- Cerrar sesión en la parte inferior -->
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" 
                    class="w-full rounded-xl flex items-center px-4 py-3 hover:bg-red-400  hover:text-white transition-colors" style="text-align: left;">
                    <div class="sidebar-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span class="sidebar-item-text ml-3">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content flex-grow p-4 md:p-6 transition-all duration-300">
        @yield('content')
    </main>
</div>
@else
<main class="flex-grow max-w-7xl mx-auto px-4 py-3">
    @yield('content')
</main>
@endif
 <!-- Botón de volver arriba mejorado -->
<button 
    id="scrollToTopBtn" 
    class="fixed bottom-8 right-8 p-3 fondo-amarillo text-black rounded-full shadow-lg hover:bg-yellow-500 transition-all duration-300 opacity-0 invisible z-50 transform hover:scale-110"
    aria-label="Volver arriba"
>
    <i class="fas fa-chevron-up text-xl"></i>
</button>      
  <!-- Footer -->
<footer class="fondo-nuevo text-black py-10 mt-10">
    <div class="container mx-auto px-6">
        <!-- Logo -->
        <div class="mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('storage/logo-blanco.png') }}" alt="Permuta2" class="h-24">
            </a>
        </div>

        <!-- Contenido principal -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 border-t border-e9af1b pt-8">
            <!-- Columna 1 -->
            <div>
                <h3 class="font-semibold mb-4 text-black">PERMUTA2</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('nosotros') }}" class="hover:underline">Nosotros</a></li>
                    <li><a href="{{ route('como.permutar') }}" class="hover:underline">¿Cómo Permutar?</a></li>
                    <li><a href="{{ route('users.index') }}" class="hover:underline">Permutadores</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:underline">Preguntas frecuentes</a></li>
                </ul>
            </div>

            <!-- Columna 2 -->
            <div>
                <h3 class="font-semibold mb-4 text-black">CATEGORÍAS DESTACADAS</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('categories.show', ['slug' => 'accesorios-vehiculos']) }}" class="hover:underline">Accesorios Vehículos</a></li>
                    <li><a href="{{ route('categories.show', ['slug' => 'computacion']) }}" class="hover:underline">Computación</a></li>
                    <li><a href="{{ route('categories.show', ['slug' => 'celulares-y-telefonos']) }}" class="hover:underline">Celulares y Teléfonos</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:underline">Ver todas</a></li>
                </ul>
            </div>

            <!-- Columna 3 -->
            <div>
                <h3 class="font-semibold mb-4 text-black">LEGAL</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('privacy') }}" class="hover:underline">Políticas de privacidad</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:underline">Términos y condiciones</a></li>
                </ul>
            </div>

            <!-- Columna 4 -->
<div>
    <h3 class="font-semibold mb-4 text-black">Síguenos en Redes</h3>
    <div class="flex items-center justify-start space-x-2">
        <a href="#" class="flex items-center justify-center w-10 h-10 hover:text-gray-700 transition-transform hover:-translate-y-1" aria-label="YouTube">
            <i class="fab fa-youtube text-xl"></i>
        </a>
        <a href="#" class="flex items-center justify-center w-10 h-10 hover:text-gray-700 transition-transform hover:-translate-y-1" aria-label="Facebook">
            <i class="fab fa-facebook text-xl"></i>
        </a>
        <a href="#" class="flex items-center justify-center w-10 h-10 hover:text-gray-700 transition-transform hover:-translate-y-1" aria-label="Instagram">
            <i class="fab fa-instagram text-xl"></i>
        </a>
        <a href="#" class="flex items-center justify-center w-10 h-10 hover:text-gray-700 transition-transform hover:-translate-y-1" aria-label="LinkedIn">
            <i class="fab fa-linkedin text-xl"></i>
        </a>
    </div>
</div>
        </div>

        <!-- Línea inferior -->
        <div class="mt-10 border-t border-e9af1b pt-6 text-sm flex flex-col md:flex-row justify-between items-center text-black">
            <p>© {{ date('Y') }} Permuta2. Todos los derechos reservados</p>
            <div class="space-x-4 mt-3 md:mt-0">
                <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                <a href="{{ route('faq') }}" class="hover:underline">Ayuda</a>
            </div>
        </div>
    </div>
</footer>

    </div>
    
      @livewireScripts
    <script>
        // Script del menú móvil
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            
            if (mobileMenuButton && mobileMenu) {
                // Abrir menú móvil
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.add('active');
                    mobileMenuOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
                
                // Cerrar menú móvil
                function closeMobileMenu() {
                    mobileMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                mobileMenuClose.addEventListener('click', closeMobileMenu);
                mobileMenuOverlay.addEventListener('click', closeMobileMenu);
                
                // Cerrar menú al hacer clic en un enlace (opcional)
                const mobileMenuLinks = mobileMenu.querySelectorAll('a');
                mobileMenuLinks.forEach(link => {
                    link.addEventListener('click', closeMobileMenu);
                });
            }
        });

        // Script del mega menú de categorías
        document.addEventListener('DOMContentLoaded', function() {
            const megaMenuContainer = document.querySelector('.mega-menu-container');
            
            if (megaMenuContainer && window.innerWidth >= 768) {
                let hideTimeout;
                
                // Función para mostrar el menú
                function showMenu() {
                    const megaMenu = megaMenuContainer.querySelector('.mega-menu');
                    if (megaMenu) {
                        clearTimeout(hideTimeout);
                        megaMenu.classList.add('active');
                    }
                }
                
                // Función para ocultar el menú con retardo
                function hideMenu() {
                    const megaMenu = megaMenuContainer.querySelector('.mega-menu');
                    if (megaMenu) {
                        hideTimeout = setTimeout(() => {
                            megaMenu.classList.remove('active');
                        }, 150);
                    }
                }
                
                // Eventos para el contenedor
                megaMenuContainer.addEventListener('mouseenter', showMenu);
                megaMenuContainer.addEventListener('mouseleave', hideMenu);
                
                // También aplicar a los elementos hijos del menú
                const megaMenu = megaMenuContainer.querySelector('.mega-menu');
                if (megaMenu) {
                    megaMenu.addEventListener('mouseenter', showMenu);
                    megaMenu.addEventListener('mouseleave', hideMenu);
                }
            }
        });

        // Script mejorado para el sidebar sticky
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebar-toggle');
            const mainContent = document.querySelector('.main-content');

            // Solo inicializar si estamos en desktop y los elementos existen
            if (sidebar && toggleButton && window.innerWidth >= 768) {
                // Función para calcular y establecer la altura máxima
                function setSidebarMaxHeight() {
                    const viewportHeight = window.innerHeight;
                    const maxHeight = viewportHeight - 40;
                    sidebar.style.maxHeight = `${maxHeight}px`;
                }

                // Inicializar altura
                setSidebarMaxHeight();

                // Recuperar estado del sidebar
                const isExpanded = localStorage.getItem('sidebarExpanded') === 'true';
                if (isExpanded) {
                    sidebar.classList.add('expanded');
                }

                // Toggle del sidebar
                toggleButton.addEventListener('click', function() {
                    sidebar.classList.toggle('expanded');
                    localStorage.setItem('sidebarExpanded', sidebar.classList.contains('expanded'));
                });

                // Recalcular en resize
                window.addEventListener('resize', setSidebarMaxHeight);

                // Ajustar en pantallas pequeñas - OCULTAR COMPLETAMENTE
                function handleSidebarOnMobile() {
                    if (window.innerWidth < 768) {
                        sidebar.style.display = 'none';
                    } else {
                        sidebar.style.display = 'block';
                        setSidebarMaxHeight();
                    }
                }

                window.addEventListener('resize', handleSidebarOnMobile);
            } else if (sidebar && window.innerWidth < 768) {
                // Asegurar que esté oculto en móvil
                sidebar.style.display = 'none';
            }
        });

        // Script del dropdown de perfil - Solo se ejecuta si existe
        document.addEventListener('DOMContentLoaded', function() {
            const profileButton = document.getElementById('profile-button');
            const profileDropdown = document.getElementById('profile-dropdown');
            
            if (profileButton && profileDropdown) {
                // Cerrar el dropdown al cargar la página
                profileDropdown.classList.remove('open');
                
                // Alternar el dropdown al hacer clic
                profileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('open');
                });
                
                // Cerrar el dropdown al hacer clic fuera
                document.addEventListener('click', function(e) {
                    if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.remove('open');
                    }
                });
                
                // Prevenir que el dropdown se cierre al hacer clic dentro de él
                profileDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });

        // Script del buscador - Solo se ejecuta si existe el formulario
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.querySelector('form[action="{{ route('products.index') }}"]');
            
            if (searchForm) {
                const searchTypeInput = document.getElementById('search-type');
                const searchTypeWant = document.getElementById('search-type-want');
                const searchTypeHave = document.getElementById('search-type-have');
                
                // Función para cambiar el tipo de búsqueda
                function setSearchType(type) {
                    if (searchTypeInput) {
                        searchTypeInput.value = type;
                    }
                    if (type === 'want') {
                        searchTypeWant?.classList.add('fondo-naranjo', 'text-white');
                        searchTypeWant?.classList.remove('buscador', 'text-gray-700');
                        searchTypeHave?.classList.add('buscador', 'text-gray-700');
                        searchTypeHave?.classList.remove('fondo-naranjo', 'text-white');
                    } else {
                        searchTypeHave?.classList.add('fondo-naranjo', 'text-white');
                        searchTypeHave?.classList.remove('buscador', 'text-gray-700');
                        searchTypeWant?.classList.add('buscador', 'text-gray-700');
                        searchTypeWant?.classList.remove('fondo-naranjo', 'text-white');
                    }
                }

                // Eventos para los botones
                searchTypeWant?.addEventListener('click', function() {
                    setSearchType('want');
                });

                searchTypeHave?.addEventListener('click', function() {
                    setSearchType('have');
                });

                // Manejar envío del formulario
                searchForm.addEventListener('submit', function(e) {
                    const searchInput = this.querySelector('input[name="q"]');
                    if (searchInput && !searchInput.value.trim()) {
                        e.preventDefault();
                        searchInput.focus();
                    }
                });
            }
        });

        // SCRIPT DE NOTIFICACIONES MEJORADO - ÚNICO Y CORRECTO
         document.addEventListener('DOMContentLoaded', function() {
            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationBadge = document.getElementById('notification-badge');
            
            // Verificar que existan los elementos necesarios
            if (!notificationButton || !notificationDropdown) {
                return;
            }

            // Función para marcar notificaciones como leídas
            async function markNotificationsAsRead() {
                const unreadNotifications = document.querySelectorAll('.unread-notification');
                
                if (unreadNotifications.length > 0) {
                    const notificationIds = Array.from(unreadNotifications).map(item => {
                        return item.closest('.notification-item').dataset.notificationId;
                    });

                    try {
                        const response = await fetch('{{ route("notifications.markMultipleAsRead") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                notification_ids: notificationIds
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            // Actualizar la interfaz
                            unreadNotifications.forEach(notification => {
                                notification.classList.remove('bg-gray-100', 'unread-notification');
                                notification.classList.add('bg-white');
                            });
                            
                            // Ocultar el badge
                            if (notificationBadge) {
                                notificationBadge.style.display = 'none';
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            }
            
            // Event listener para el botón de notificaciones
            notificationButton.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                const isHidden = notificationDropdown.classList.contains('hidden');
                
                if (isHidden) {
                    // Mostrar dropdown
                    notificationDropdown.classList.remove('hidden');
                    
                    // Marcar notificaciones como leídas inmediatamente
                    markNotificationsAsRead();
                    
                } else {
                    // Ocultar dropdown
                    notificationDropdown.classList.add('hidden');
                }
            });
            
            // Cerrar dropdown al hacer clic fuera
            document.addEventListener('click', function(event) {
                const isClickInside = notificationButton.contains(event.target) || 
                                     notificationDropdown.contains(event.target);
                
                if (!isClickInside && !notificationDropdown.classList.contains('hidden')) {
                    notificationDropdown.classList.add('hidden');
                }
            });

            // Prevenir que el dropdown se cierre cuando se hace clic dentro de él
            notificationDropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
        
        // Botón de volver arriba
document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    
    if (scrollToTopBtn) {
        // Mostrar/ocultar botón según el scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });

        // Desplazarse suavemente hacia arriba al hacer clic
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // También agregar soporte para navegadores antiguos
        scrollToTopBtn.addEventListener('click', function() {
            // Fallback para navegadores que no soportan scrollTo con behavior
            if (!('scrollBehavior' in document.documentElement.style)) {
                const scrollDuration = 300;
                const scrollStep = -window.scrollY / (scrollDuration / 15);
                
                const scrollInterval = setInterval(function() {
                    if (window.scrollY !== 0) {
                        window.scrollBy(0, scrollStep);
                    } else {
                        clearInterval(scrollInterval);
                    }
                }, 15);
            }
        });
    }
});
    </script>

    @stack('scripts')
    @include('components.alert')

</body>
</html>