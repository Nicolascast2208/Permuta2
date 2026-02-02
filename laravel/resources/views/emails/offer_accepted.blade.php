<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Oferta aceptada! - Permuta2</title>
    <style>
        /* Mismo estilo base que el anterior */
        body {
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background-color: #ffb806;
            padding: 30px 20px;
            text-align: center;
        }
        
        .content {
            padding: 40px 30px;
            color: #333333;
        }
        
        .button {
            display: inline-block;
            background-color: #ea502e;
            color: white !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 30px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        
        .footer {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            color: #666666;
            font-size: 12px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .offer-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
<body>
    <div class="container">
      <div class="header">
    <img src="{{ asset('storage/logo-blanco.png') }}" 
         alt="Permuta2 Logo" 
         style="height: 80px; width: auto;">


</div>
        
        
        <div class="content">
            <h2 class="text-center" style="color: #333; margin-bottom: 10px;">¡Felicidades! Tu oferta fue aceptada</h2>
            
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <p>¡Buenas noticias! Tu oferta para el producto <strong>"{{ $productRequested->title }}"</strong> ha sido aceptada.</p>
            
            <div class="offer-details">
                <h3 style="margin-top: 0; color: #ea502e;">Próximos pasos:</h3>
                <ol>
                    <li>Coordina la entrega con el otro usuario a través del chat</li>
                    <li>Asegúrate de que ambos productos estén en perfecto estado</li>
                    <li>Confirma la entrega una vez completado la permuta</li>
                </ol>
            </div>
            
    <div class="text-center">
    @if($chat)
        <a href="{{ route('chat.show', $chat) }}" class="button">
            IR AL CHAT
        </a>
    @else
        <a href="{{ route('dashboard.received-offers') }}" class="button">
            VER OFERTA
        </a>
        <p style="font-size: 14px; color: #666; margin-top: 10px;">
            El chat estará disponible pronto
        </p>
    @endif
</div>
            
            <p>Si tienes alguna pregunta, contáctanos a <strong>soporte@permuta2.cl</strong></p>
            
            <p style="margin-top: 30px;">
                ¡Que tengas una excelente permuta!<br>
                <strong>El equipo de Permuta2</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Permuta2. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>