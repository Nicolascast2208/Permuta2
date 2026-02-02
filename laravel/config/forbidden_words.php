<?php

return [
    'words' => [
        // Correos y derivados
        'gmail', 'hotmail', 'outlook', 'live', 'yahoo', 'icloud', 'proton',
        'zoho', 'mail', 'correo', 'email', 'e-mail', 'inbox', 'com', 'cl',
        
        // Telefonía
        'telefono', 'teléfono', 'fono', 'celular', 'movil', 'móvil',
        'whatsapp', 'wasap', 'wsp', 'wp', 'cel', 'tel', 'guasap', 'uatza',
        'wasapp', 'instagran', 'g-mail', 'insta.gram',
        
        // Redes sociales
        'instagram', 'insta', 'ig', 'facebook', 'fb', 'messenger', 'twitter',
        'x', 'tiktok', 'telegram', 'tg', 'discord', 'linkedin', 'face', 'meta',
        'snap', 'snapchat', 'line', 'wechat', 'viber', 'signal',
        
        // Plataformas de contacto directo
        'zoom', 'meet', 'teams', 'skype', 'facetime',
        
        // Pagos e información bancaria
        'rut', 'banco', 'transferencia', 'paypal', 'mercadopago', 'mercado',
        'cuenta', 'transfiere', 'alias', 'datos', 'bancarios',
        
        // Direcciones y contacto físico
        'direccion', 'dirección', 'domicilio', 'oficina', 'local', 'sucursal',
        
        // Variantes "disfraz"
        'arroba', 'at', 'dot', 'punto', 'guion', 'dash', 'dm', 'md', 'inbox',
        'pv', 'priv', 'deletreado', 'llamada', 'llamadas', 'llamar', 'llamame',
        'llamarme', 'numero', 'número', 'contacto',
    ],
    
    'phrases' => [
        'escríbelo separado',
        'con espacios',
        'sin símbolos',
        'en palabras',
        'g m a i l',
        'guasap',
        'uatsap',
        'insta_gram',
        'x interno',
        'te mando los datos',
        'para transferirte',
        'dame tu rut',
        'fuera de acá',
        'fuera de la app',
        'fuera de la página',
        'hablemos afuera',
        'mejor por otro lado',
        'por otro medio',
    ],
    
    'patterns' => [
        'email_pattern' => '/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/i',
        'phone_pattern' => '/\+?\d{1,3}[-.\s]?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/',
        'whatsapp_pattern' => '/whatsapp\s*[:.]?\s*\+?\d{1,3}[-.\s]?\d{1,4}[-.\s]?\d{1,9}/i',
        'social_pattern' => '/(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin|tiktok|whatsapp|telegram|snapchat)\.(com|org|net|io|[a-z]{2,})/i',
    ]
];