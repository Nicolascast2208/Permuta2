<nav>
    <a href="{{ route('home') }}">Inicio</a>
    
    @auth
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('products.index') }}">Productos</a>
        <span>{{ auth()->user()->alias }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Cerrar Sesión</button>
        </form>
    @else
        <a href="{{ route('login') }}">Iniciar Sesión</a>
        <a href="{{ route('register') }}">Registrarse</a>
    @endauth
</nav>