@extends('layouts.app')

@section('content')
        <!-- Encabezado -->
        <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
            <h2 class="text-3xl font-bold">RECUPERAR CONTRASEÑA</h2>
            <p class="mt-2 text-sm">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
        </div>
<div class="flex flex-col items-center justify-start py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    
    <div class="max-w-md w-full">


        @if (session('status'))
            <div class="rounded-xl bg-green-50 p-4 mb-6 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf

            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <div class="relative">
                    <input id="email" name="email" type="email" required autofocus
                           value="{{ old('email') }}"
                           class="mt-1 block w-full px-3 py-2.5 bg-white border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                           placeholder="ejemplo@dominio.com">
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botón de Envío -->
            <div class="pt-2">
                <button type="submit"
                        class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    Enviar Enlace de Restablecimiento
                </button>
            </div>

            <!-- Volver al login -->
            <div class="text-center pt-4">
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Volver al Inicio de Sesión
                </a>
            </div>
        </form>
    </div>
</div>
@endsection