<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta enviada - Permuta2</title>
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
</head>
<body>
    <div class="container">
     <div class="header">
    <img src="{{ asset('storage/logo-blanco.png') }}" 
         alt="Permuta2 Logo" 
         style="height: 80px; width: auto;">


</div>
        
        
        <div class="content">
            <h2 class="text-center" style="color: #333; margin-bottom: 10px;">¡Oferta enviada exitosamente!</h2>
            
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <p>Tu oferta ha sido enviada correctamente a <strong>{{ $toUser->name }}</strong> para el producto:</p>
            
            <div class="offer-details">
                <h3 style="margin-top: 0; color: #ea502e;">{{ $productRequested->title }}</h3>
                <p><strong>Productos que ofreciste:</strong></p>
                <ul>
                    @foreach($offer->productsOffered as $product)
                        <li>{{ $product->title }}</li>
                    @endforeach
                </ul>
            </div>
            
            <p>Ahora debes esperar a que {{ $toUser->name }} revise tu oferta y te responda.</p>
            
            <div class="text-center">
                <a href="{{ route('offers.success', $offer) }}" class="button">
                    VER DETALLES DE LA OFERTA
                </a>
            </div>
            
            <p style="margin-top: 30px;">
                ¡Gracias por usar Permuta2!<br>
                <strong>El equipo de Permuta2</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Permuta2. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>