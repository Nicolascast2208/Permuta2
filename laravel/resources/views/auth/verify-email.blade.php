@extends('layouts.guest')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Verifica tu correo electrónico</h2>
        
        <div class="mb-4 text-sm text-gray-600">
            Gracias por registrarte! Antes de comenzar, por favor verifica tu correo electrónico 
            haciendo clic en el enlace que te enviamos. Si no recibiste el correo, te podemos enviar otro.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                        class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                    Reenviar correo de verificación
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="text-gray-600 hover:text-gray-900 underline text-sm">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection