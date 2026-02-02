<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago de comisión requerido - Permuta2</title>
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
        
        .urgent {
            background-color: #fff3e0;
            border-left: 4px solid #ffb806;
            padding: 15px;
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
            <h2 class="text-center" style="color: #333; margin-bottom: 10px;">Pago de comisión requerido</h2>
            
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <div class="urgent">
                <p><strong>⚠️ Acción requerida:</strong> {{ $emailMessage }}</p> <!-- Cambiado a $emailMessage -->
            </div>
            
            <div class="offer-details">
                <h3 style="margin-top: 0; color: #ea502e;">Detalles de la oferta:</h3>
                <p><strong>Producto solicitado:</strong> {{ $offer->productRequested->title }}</p>
                
                                @if($paymentType === 'offered')
                <p><strong>Tus productos ofrecidos:</strong></p>
                <ul>
                    @foreach($offer->productsOffered as $product)
                        <li>{{ $product->title }} - ${{ number_format($product->price_reference, 0, ',', '.') }}</li>
                    @endforeach
                </ul>
               
            </div>
            
            <div class="text-center">
                <a href="{{ route('checkout.commission', $offer) }}" class="button">
                    PAGAR COMISIÓN
                </a>
            </div>
             @else


    <div class="text-center">
        <a href="{{ route('checkout.commission-offered', $offer) }}" class="button">
           PAGAR COMISIÓN
        </a>
    </div>
@endif
            
            <p><strong>Importante:</strong> La permuta no se completará hasta que ambos usuarios hayan pagado sus comisiones.</p>
            
            <p>Si tienes problemas con el pago, contáctanos a <strong>soporte@permuta2.cl</strong></p>
            
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