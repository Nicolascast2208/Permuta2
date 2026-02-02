<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permuta2 - @yield('title')</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
     
    @livewireStyles
    @stack('styles')
    <style>
        .bg-blue-600 {
            --tw-bg-opacity: 1;
            background-color: rgb(236 101 35)!important;
        }
        .fondo-personalizado {
            background-image: url('{{ asset('storage/images/fondo-banner-pattern.jpg') }}');
            background-size: cover;
            background-position: right top;
        }
        .fondo-celeste{
            background: linear-gradient(to bottom, #587c98 0%, #8ba3b7 45%, #ffffff 100%);
        }
        .huincha{
            background:#f49c22;
        }
        .buscador{
            background:#507593;
        }
        .bg-gray-50 {
            background: #efefef;
        }
        .border-orange-400{
            border-color: rgb(236 101 35)!important;
        }
        .texto-naranjo{
            color:rgb(236 101 35) !important;
        }
        .texto-amarillo{
            color:#ffce00;
        }
        .fondo-amarillo{
            background:#ffce00;
        }
        .fondo-amarillo-header{
            background:#fddd55;
        }
        .fondo-naranjo{
            background:#ea502e;
        }
        .texto-naranjo2{
            color:#ea502e;
        }
        .bg-footerx{
            background:#2d587b;
        }
        .fondo-gris{
            background:#efefef;
        }
        .fondo-menu-dashboard{
            background:#f2cd30;
        }
        .texto-menu{
            color: #00378c;
        }
        .text-celestex {
            color: #69ffff;
        }
        .fondo-nuevo{
            background:#ffb806;
        }
        
        /* Estilos para el mega menú de categorías */
        .mega-menu {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        .mega-menu.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        .mega-menu-container:hover .mega-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        /* PUENTE INVISIBLE - Solución al gap */
        .mega-menu-container::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: transparent;
            z-index: 51;
        }
        
        .mega-menu {
            top: 100%;
            margin-top: 10px;
        }

        .category-column {
            min-width: 200px;
        }
        .category-header {
            transition: all 0.2s ease;
        }
        .category-header:hover {
            background-color: #fef3e2;
            border-radius: 0.5rem;
        }

        /* Estilos para el menú móvil */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }
        
        .mobile-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 85%;
            max-width: 320px;
            height: 100%;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            z-index: 999;
            overflow-y: auto;
        }
        
        .mobile-menu.active {
            left: 0;
        }
        
        .mobile-menu-overlay.active {
            display: block;
        }
        
        /* Ocultar elementos en móviles */
        @media (max-width: 767px) {
            .desktop-only {
                display: none !important;
            }
            
            .mobile-menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid #e5e5e5;
            }
            
            .mobile-menu-content {
                padding: 1rem;
            }
            
            .mobile-menu-section {
                margin-bottom: 1.5rem;
            }
            
            .mobile-menu-section-title {
                font-weight: bold;
                margin-bottom: 0.5rem;
                color: #333;
                border-bottom: 1px solid #e5e5e5;
                padding-bottom: 0.5rem;
            }
            
            .mobile-menu-link {
                display: block;
                padding: 0.75rem 0;
                color: #555;
                border-bottom: 1px solid #f5f5f5;
            }
            
            .mobile-menu-link:last-child {
                border-bottom: none;
            }
            
            .mobile-menu-link:hover {
                color: #3b82f6;
            }
            
            .mobile-user-info {
                display: flex;
                align-items: center;
                padding: 1rem 0;
                border-bottom: 1px solid #e5e5e5;
                margin-bottom: 1rem;
            }
            
            .mobile-user-avatar {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                overflow: hidden;
                margin-right: 1rem;
                border: 2px solid #065f46;
            }
            
            .mobile-user-details {
                flex: 1;
            }
            
            .mobile-user-name {
                font-weight: bold;
                color: #333;
            }
            
            .mobile-user-email {
                font-size: 0.875rem;
                color: #666;
            }
            
            /* Ocultar mega menú en móvil */
            .mega-menu-container {
                display: none;
            }
            .mobile-categories-link {
                display: block;
            }
            
            /* Ocultar el puente invisible en móvil */
            .mega-menu-container::before {
                display: none;
            }
        }
        
        @media (min-width: 768px) {
            .mobile-only {
                display: none !important;
            }
        }

        .btn-orange {
            background-color: #ea580c;
            color: white;
            border: 2px solid #ea580c;
            border-radius: 9999px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            text-align: center;
            display: inline-block;
        }
        .btn-orange:hover {
            background-color: #c2410c;
            border-color: #c2410c;
        }

        .btn-outline-orange {
            background-color: white;
            color: #ea580c;
            border: 2px solid #ea580c;
            border-radius: 9999px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            text-align: center;
            display: inline-block;
        }
        .btn-outline-orange:hover {
            background-color: #ea580c;
            color: white;
        }
    </style>
</head>
<body class="bg-white">
    
    <!-- Header -->
    <header class="fondo-nuevo">
        <!-- 1️⃣ BARRA SUPERIOR -->
        <div class="hidden md:block huincha">    
            <div class="flex justify-between items-center text-sm px-6 py-2 max-w-7xl mx-auto">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-map-marker-alt fa-lg"></i> 
                    <span class="font-semibold">Santiago, Chile</span>
                </div>
                <div class="flex space-x-4">
                    <!-- Enlaces para usuarios no autenticados -->
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
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-y-4">
                <!-- LOGO + MENU BUTTON (mobile) -->
                <div class="flex items-center justify-between md:justify-start md:col-span-3">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('storage/logo-blanco.png') }}" alt="Permuta2 Logo" class="h-20 sm:h-32 md:h-26 w-auto">
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
                        <button type="button" id="search-type-want" class="px-6 py-1 fondo-naranjo text-white font-semibold text-sm rounded-tl-xl transition-colors search-tab active">QUE BUSCO</button>
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
                <div class="md:col-span-2 hidden md:flex items-center justify-center md:justify-center w-full h-full pt-7">
                    <a href="{{ route('products.createx') }}" 
                       class="fondo-naranjo text-white font-semibold px-6 py-2 rounded-full shadow hover:bg-red-700 transition flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>PUBLICAR</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- separador -->
        <div class="border-t border-yellow-400"></div>

        <!-- MENÚ INFERIOR / CATEGORÍAS -->
        <nav class="hidden md:block max-w-7xl mx-auto px-6 py-3 ">
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
                                        $categories = \App\Models\Category::whereNull('parent_id')->get();
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
                <a href="{{ route('faq') }}" class="mobile-menu-link">
                    <i class="fas fa-info-circle w-5 mr-2"></i> Ayuda
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
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 py-3">
        @yield('content')
    </main>
    
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 border-t border-[#e19f00] pt-8">
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
                    <div class="flex items-center justify-start space-x-4">
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
            <div class="mt-10 border-t border-[#e19f00] pt-6 text-sm flex flex-col md:flex-row justify-between items-center text-black">
                <p>© {{ date('Y') }} Permuta2. Todos los derechos reservados</p>
                <div class="space-x-4 mt-3 md:mt-0">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <a href="{{ route('faq') }}" class="hover:underline">Ayuda</a>
                </div>
            </div>
        </div>
    </footer>
    
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

        // Scripts existentes de Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('offer-sent', () => {
                setTimeout(() => {
                    Livewire.dispatch('close-offer-modal');
                }, 3000);
            });

            Livewire.on('open-offer-modal', () => {
                Livewire.dispatch('refresh-products');
            });
        });

        document.addEventListener('livewire:update', function () {
            const offerButton = document.querySelector('[wire\\:click="submitOffer"]');
            if (offerButton) {
                const isDisabled = window.livewire?.find(offerButton.closest('[wire\\:id]')?.getAttribute('wire:id'))?.get('selectedOfferProduct') ? false : true;
                offerButton.disabled = isDisabled;
            }
        });
    </script>

    @stack('scripts')
    @include('components.alert')
</body>
</html>