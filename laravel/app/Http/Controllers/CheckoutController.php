<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

class CheckoutController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct()
    {
        // Configurar Mercado Pago solo si tenemos token
        $accessToken = config('services.mercadopago.access_token');
        if ($accessToken) {
            MercadoPagoConfig::setAccessToken($accessToken);
            
        }
    }
    
    public function show(Product $product)
    {
        $type = 'publication'; 
        
        if ($product->user_id !== auth()->id() || $product->was_paid) {
            abort(403, 'No tienes permiso para acceder a esta página');
        }

        $commission = 1000;
        
        return view('checkout', compact('product', 'commission', 'type'));
    }
    
    public function showCommission(Offer $offer)                                                                                                        
    {
        // Cargar las relaciones necesarias
        $offer->load(['productRequested', 'productsOffered']);
        
        $product = $offer->productRequested;
        
        if (!$product) {
            abort(404, 'Producto solicitado no encontrado');
        }
        
        $commission = $product->price_reference * 0.05;
        
        return view('checkout-commission', compact('offer', 'product', 'commission'));
    }

    public function processCommission(Offer $offer)
    {
        $this->authorize('pay-commission', $offer);
        return app(PaymentController::class)->processCommission($offer);
    }

    public function showCommissionOffered(Offer $offer)
    {
        if (auth()->id() !== $offer->from_user_id) {
            abort(403, 'No tienes permiso para acceder a esta página');
        }

        // Cargar los productos ofrecidos
        $offer->load(['productsOffered']);

        // Obtener productos ofrecidos no pagados
        $unpaidProducts = $offer->productsOffered->filter(function ($product) {
            return !$product->was_paid;
        });

        if ($unpaidProducts->isEmpty()) {
            return redirect()->route('dashboard.received-offers')
                ->with('error', 'Todos los productos ofrecidos ya fueron pagados');
        }

        // Calcular comisión total (8% de la suma de productos no pagados)
        $totalPrice = $unpaidProducts->sum('price_reference');
        $commission = $totalPrice * 0.05;
        
        return view('checkout-commission-offered', compact('offer', 'unpaidProducts', 'commission'));
    }

    public function processCommissionOffered(Offer $offer)
    {
        if (auth()->id() !== $offer->from_user_id) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        return app(PaymentController::class)->processCommissionOffered($offer);
    }
    
    public function processPayment(Product $product)
    {
        if ($product->user_id !== auth()->id() || $product->was_paid) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        try {
            // Crear registro de pago
            $payment = Payment::create([
                'buy_order' => $product->id,
                'session_id' => $product->user->alias,
                'amount' => 1000,
                'type' => 'publication',
                'status' => 'pending',
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'payment_method' => 'mercadopago',
                'metadata' => [
                    'product_id' => $product->id,
                    'type' => 'publication',
                    'product_title' => $product->title
                ]
            ]);

            // Configurar Mercado Pago
            $accessToken = config('services.mercadopago.access_token');
            if (empty($accessToken)) {
                throw new \Exception('Token de Mercado Pago no configurado.');
            }
            
            MercadoPagoConfig::setAccessToken($accessToken);
            
            // Crear cliente de preferencia
            $client = new PreferenceClient();
            
            // Crear preferencia de pago
            $preferenceData = [
                "items" => [
                    [
                        "id" => "publication-{$product->id}",
                        "title" => "Publicación de producto: " . substr($product->title, 0, 100),
                        "description" => "Costo de publicación del producto",
                        "quantity" => 1,
                        "currency_id" => "CLP",
                        "unit_price" => (float) 1000
                    ]
                ],
                "payer" => [
                    "name" => auth()->user()->name,
                    "email" => auth()->user()->email,
                    "identification" => [
                        "type" => "RUT",
                        "number" => auth()->user()->rut ?? "00000000"
                    ]
                ],
                "back_urls" => [
                    "success" => route('payment.return'),
                    "failure" => route('payment.failure'),
                    "pending" => route('payment.return')
                ],
                "auto_return" => "approved",
                "notification_url" => route('payment.webhook'),
                "external_reference" => "payment_{$payment->id}",
                "statement_descriptor" => config('app.name', 'Permutador'),
                "metadata" => [
                    "payment_id" => $payment->id,
                    "product_id" => $product->id,
                    "type" => "publication"
                ]
            ];
            
            Log::info('Creando preferencia Mercado Pago para publicación', $preferenceData);
            
            $preference = $client->create($preferenceData);
            
            if (!$preference || !isset($preference->id)) {
                throw new \Exception('No se pudo crear la preferencia en Mercado Pago');
            }
            
            // Actualizar payment con mp_preference_id
            $payment->update([
                'mp_preference_id' => $preference->id
            ]);
            
            Log::info('Preferencia creada exitosamente', [
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point ?? 'N/A'
            ]);

            // Redirigir a Mercado Pago
            $redirectUrl = $preference->init_point;
            
            if (!$redirectUrl) {
                throw new \Exception('No se pudo obtener la URL de redirección de Mercado Pago');
            }
            
            // Guardar payment_id en sesión
            session()->put([
                'mp_payment_id' => $payment->id,
                'mp_preference_id' => $preference->id
            ]);
            
            return redirect()->away($redirectUrl);

        } catch (\Exception $e) {
            Log::error('Error en processPayment: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
}