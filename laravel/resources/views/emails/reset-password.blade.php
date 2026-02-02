<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablece tu contraseña - Permuta2</title>
    <style>
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
        
        .logo {
            max-width: 200px;
            height: auto;
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
        
        .security-notice {
            background-color: #fff3e0;
            border-left: 4px solid #ffb806;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header con logo -->
      <div class="header">
    <img src="{{ asset('storage/logo-blanco.png') }}" 
         alt="Permuta2 Logo" 
         style="height: 80px; width: auto;">


</div>
        
        
        <!-- Contenido principal -->
        <div class="content">
            <h2 class="text-center" style="color: #333; margin-bottom: 10px;">Restablece tu contraseña</h2>
            <p class="text-center" style="color: #666; margin-bottom: 30px;">Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
            
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
            
            <div class="text-center">
                <a href="{{ $resetUrl }}" class="button">
                    RESTABLECER CONTRASEÑA
                </a>
                
                <p style="margin-top: 20px; font-size: 14px; color: #666;">
                    Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                    <span style="color: #ea502e; word-break: break-all;">{{ $resetUrl }}</span>
                </p>
            </div>
            
            <div class="security-notice">
                <strong>🔒 Importante:</strong> Este enlace expirará en 24 horas por seguridad.
            </div>
            
            <p>Si no solicitaste restablecer tu contraseña, ignora este mensaje.</p>
            
            <p style="margin-top: 30px;">
                ¡Gracias por ser parte de nuestra comunidad!<br>
                <strong>El equipo de Permuta2</strong>
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Permuta2. Todos los derechos reservados.</p>
            <p>
                <a href="{{ url('/privacy') }}" style="color: #666; margin: 0 10px;">Políticas de privacidad</a> | 
                <a href="{{ url('/terms') }}" style="color: #666; margin: 0 10px;">Términos y condiciones</a>
            </p>
        </div>
    </div>
</body>
</html>