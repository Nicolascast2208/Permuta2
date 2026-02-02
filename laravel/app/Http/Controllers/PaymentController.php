<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\OfferReceived;
use App\Events\TradeAccepted;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;

class PaymentController extends Controller
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

    public function commission(Offer $offer)
    {
        $this->authorize('pay-commission', $offer);
        return redirect()->route('checkout.commission', $offer);
    }

    public function processCommission(Offer $offer)
    {
        try {
            $this->authorize('pay-commission', $offer);
            
            $offer->load(['productRequested' => function($query) {
                $query->select('id', 'price_reference', 'title');
            }]);
            
            if (!$offer->productRequested) {
                throw new \Exception("Producto solicitado no encontrado");
            }
            
            if ($offer->productRequested->price_reference <= 0) {
                throw new \Exception("Precio de referencia inválido");
            }
            
            $amount = round($offer->productRequested->price_reference * 0.05);
            
            // Crear registro de pago
            $payment = Payment::create([
                'buy_order' => "OFFER-{$offer->id}",
                'session_id' => "OFFER-{$offer->id}-" . uniqid(),
                'amount' => $amount,
                'type' => 'commission_requested',
                'status' => 'pending',
                'user_id' => auth()->id(),
                'offer_id' => $offer->id,
                'product_id' => $offer->product_requested_id,
                'payment_method' => 'mercadopago',
                'metadata' => [
                    'offer_id' => $offer->id,
                    'product_id' => $offer->product_requested_id,
                    'type' => 'commission_requested',
                    'product_title' => $offer->productRequested->title
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
                        "id" => "commission-offer-{$offer->id}",
                        "title" => "Comisión por producto solicitado - Oferta #{$offer->id}",
                        "description" => "Producto: " . substr($offer->productRequested->title ?? "Producto solicitado", 0, 200),
                        "quantity" => 1,
                        "currency_id" => "CLP",
                        "unit_price" => (float) $amount
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
                    "offer_id" => $offer->id,
                    "type" => "commission_requested"
                ]
            ];
            
            Log::info('Creando preferencia Mercado Pago para comisión', $preferenceData);
            
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
            $redirectUrl =  $preference->init_point;
            
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
            Log::error("Error en processCommission: " . $e->getMessage());
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    public function processCommissionOffered(Offer $offer)
    {
        if (auth()->id() !== $offer->from_user_id) {
            abort(403);
        }
        
        try {
            // Obtener productos ofrecidos sin pagar
            $unpaidProducts = $offer->productsOffered->filter(function($product) {
                return !$product->was_paid;
            });
            
            if ($unpaidProducts->isEmpty()) {
                throw new \Exception("No hay productos pendientes de pago");
            }
            
            $totalPrice = $unpaidProducts->sum('price_reference');
            $amount = round($totalPrice * 0.05);
            
            // Crear registro de pago
            $payment = Payment::create([
                'buy_order' => "OFFERED-{$offer->id}",
                'session_id' => auth()->id(),
                'amount' => $amount,
                'type' => 'commission_offered',
                'status' => 'pending',
                'user_id' => auth()->id(),
                'offer_id' => $offer->id,
                'payment_method' => 'mercadopago',
                'metadata' => [
                    'offer_id' => $offer->id,
                    'type' => 'commission_offered',
                    'unpaid_product_ids' => $unpaidProducts->pluck('id')->toArray(),
                    'unpaid_products_count' => $unpaidProducts->count()
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
                        "id" => "commission-offered-{$offer->id}",
                        "title" => "Comisión por productos ofrecidos - Oferta #{$offer->id}",
                        "description" => "Comisión por " . $unpaidProducts->count() . " producto(s) ofrecido(s)",
                        "quantity" => 1,
                        "currency_id" => "CLP",
                        "unit_price" => (float) $amount
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
                    "offer_id" => $offer->id,
                    "type" => "commission_offered"
                ]
            ];
            
            Log::info('Creando preferencia Mercado Pago para comisión ofrecida', $preferenceData);
            
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
            $redirectUrl =  $preference->init_point;
            
            if (!$redirectUrl) {
                throw new \Exception('No se pudo obtener la URL de redirección de Mercado Pago');
            }
            
            // Guardar payment_id en sesión
            session()->put([
                'mp_payment_id' => $payment->id,
                'mp_preference_id' => $preference->id,
                'mp_unpaid_products' => $unpaidProducts->pluck('id')->toArray()
            ]);
            
            return redirect()->away($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error("Error en processCommissionOffered: " . $e->getMessage());
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    public function return(Request $request)
    {
        Log::info('Redirección desde Mercado Pago', $request->all());
        
        $paymentId = $request->get('payment_id');
        $preferenceId = $request->get('preference_id');
        $collectionStatus = $request->get('collection_status');
        $externalReference = $request->get('external_reference');

        try {
            // Buscar el pago
            $payment = null;
            
            // Primero por payment_id de sesión
            $sessionPaymentId = session()->get('mp_payment_id');
            if ($sessionPaymentId) {
                $payment = Payment::find($sessionPaymentId);
            }
            
            // Si no, buscar por preference_id
            if (!$payment && $preferenceId) {
                $payment = Payment::where('mp_preference_id', $preferenceId)->first();
            }
            
            // Si no, buscar por external_reference
            if (!$payment && $externalReference && str_starts_with($externalReference, 'payment_')) {
                $paymentIdFromRef = str_replace('payment_', '', $externalReference);
                $payment = Payment::find($paymentIdFromRef);
            }

            if (!$payment) {
                Log::error('No se encontró el pago en return');
                return redirect()->route('payment.failure');
            }

            // Si collection_status es "approved", procesar
            if ($collectionStatus === 'approved') {
                if ($payment->isPending()) {
                    // Marcar como aprobado
                    $payment->markAsMpApproved([
                        'mp_payment_id' => $paymentId ?? 'MP-' . time(),
                        'status' => 'approved',
                        'status_detail' => 'accredited',
                        'payment_method' => $request->get('payment_type', 'account_money'),
                        'installments' => 1,
                        'transaction_amount' => $payment->amount
                    ]);
                    
                    // Procesar la lógica de negocio
                    $this->processPaymentLogic($payment);
                    
                    return redirect()->route('payment.success', [
                        'payment' => $payment->id
                    ]);
                } elseif ($payment->isApproved()) {
                    // Ya estaba aprobado, redirigir a éxito
                    return redirect()->route('payment.success', [
                        'payment' => $payment->id
                    ]);
                }
            }
            
            // Si está pendiente
            if ($collectionStatus === 'pending' || $collectionStatus === 'in_process') {
                return redirect()->route('payment.success')
                    ->with('info', 'Tu pago está pendiente. Recibirás una notificación cuando sea aprobado.');
            }
            
            // Si fue rechazado
            if ($collectionStatus === 'rejected') {
                $payment->markAsFailed('Pago rechazado por el banco');
                return redirect()->route('payment.failure');
            }

            // Estado desconocido
            return redirect()->route('payment.success')
                ->with('info', 'Estamos procesando tu pago. Recibirás una notificación cuando esté completo.');

        } catch (\Exception $e) {
            Log::error('Error en return: ' . $e->getMessage());
            return redirect()->route('payment.failure');
        }
    }

    private function processPaymentLogic(Payment $payment)
    {
        $type = $payment->type;
        $metadata = $payment->metadata;
        
        switch ($type) {
            case 'commission_requested':
                $this->processCommissionRequested($payment);
                break;
                
            case 'commission_offered':
                $this->processCommissionOfferedLogic($payment);
                break;
                
            case 'publication':
                $this->processPublicationPayment($payment);
                break;
        }
    }

    private function processCommissionRequested(Payment $payment)
    {
        $offer = $payment->offer;
        if (!$offer) return;

        $offer->update(['payment_status' => 'paid']);
        
        if ($offer->productRequested) {
            $offer->productRequested->update(['was_paid' => true]);
        }
        
        // Verificar si TODOS los productos están pagados
        $allOfferedPaid = $offer->productsOffered->every(function($product) {
            return $product->was_paid;
        });
        
        $bothPaid = $allOfferedPaid && $offer->productRequested->was_paid;
        
        if ($bothPaid) {
            $this->completeTrade($offer);
        } else {
            // Notificar al ofertante
            $unpaidProducts = $offer->productsOffered->filter(function($product) {
                return !$product->was_paid;
            });
            
            Notification::create([
                'user_id' => $offer->from_user_id,
                'type' => 'payment_required',
                'notifiable_id' => $offer->id,
                'notifiable_type' => Offer::class,
                'message' => 'El dueño del producto ha pagado su comisión. Ahora debes pagar la comisión por ' . $unpaidProducts->count() . ' producto(s) ofrecido(s) para completar la permuta.',
                'read' => false
            ]);
        }
    }

    private function processCommissionOfferedLogic(Payment $payment)
    {
        $offer = $payment->offer;
        if (!$offer) return;
        
        $metadata = $payment->metadata;
        $unpaidProductIds = $metadata['unpaid_product_ids'] ?? [];
        
        // Marcar los productos ofrecidos como pagados
        foreach ($offer->productsOffered as $product) {
            if (in_array($product->id, $unpaidProductIds)) {
                $product->update(['was_paid' => true]);
            }
        }
        
        // Verificar si TODOS los productos están pagados
        $allOfferedPaid = $offer->productsOffered->every(function($product) {
            return $product->was_paid;
        });
        
        $bothPaid = $allOfferedPaid && $offer->productRequested->was_paid;
        
        if ($bothPaid) {
            $this->completeTrade($offer);
        } else {
            // Notificar al dueño del producto solicitado
            Notification::create([
                'user_id' => $offer->to_user_id,
                'type' => 'payment_required',
                'notifiable_id' => $offer->id,
                'notifiable_type' => Offer::class,
                'message' => 'El ofertante ha pagado su comisión. Ahora debes pagar la comisión por tu producto "' . $offer->productRequested->title . '" para completar la permuta.',
                'read' => false
            ]);
        }
    }

    private function processPublicationPayment(Payment $payment)
    {
        $product = $payment->product;
        if ($product) {
            $product->update([
                'status' => 'available',
                'published' => true,
                'was_paid' => true
            ]);
        }
    }

    private function completeTrade(Offer $offer)
    {
        $offer->update([
            'status' => 'accepted',
            'payment_status' => 'paid'
        ]);
        
        $offer->productRequested->update(['status' => 'paired']);
        foreach ($offer->productsOffered as $product) {
            $product->update(['status' => 'paired']);
        }
        
        // Crear chat
        Chat::create([
            'offer_id' => $offer->id,
            'user1_id' => $offer->from_user_id,
            'user2_id' => $offer->to_user_id
        ]);
        
        // Notificar a ambos usuarios
        Notification::create([
            'user_id' => $offer->from_user_id,
            'type' => 'offer_accepted',
            'notifiable_id' => $offer->id,
            'notifiable_type' => Offer::class,
            'message' => 'Tu pago ha sido confirmado. La permuta para "' . $offer->productRequested->title . '" ha sido completado.',
            'read' => false
        ]);
        
        Notification::create([
            'user_id' => $offer->to_user_id,
            'type' => 'offer_accepted',
            'notifiable_id' => $offer->id,
            'notifiable_type' => Offer::class,
            'message' => 'El pago ha sido confirmado. La permuta para "' . $offer->productRequested->title . '" ha sido completado.',
            'read' => false
        ]);
        
        event(new TradeAccepted($offer));
    }

    public function webhook(Request $request)
    {
        try {
            Log::info('Webhook recibido de Mercado Pago', $request->all());
            
            if ($request->get('type') === 'payment') {
                $paymentId = $request->get('data.id');
                
                if (!$paymentId) {
                    Log::error('No payment ID en webhook');
                    return response()->json(['error' => 'No payment ID'], 400);
                }

                // Obtener detalles del pago desde Mercado Pago
                $client = new PaymentClient();
                $mpPayment = $client->get($paymentId);
                
                Log::info('Datos del pago desde MP webhook', [
                    'payment_id' => $paymentId,
                    'status' => $mpPayment->status ?? 'N/A'
                ]);

                // Buscar por external_reference
                $externalReference = $mpPayment->external_reference ?? null;
                $payment = null;
                
                if ($externalReference && str_starts_with($externalReference, 'payment_')) {
                    $paymentIdFromRef = str_replace('payment_', '', $externalReference);
                    $payment = Payment::find($paymentIdFromRef);
                }
                
                // Si no encontramos por external_reference, buscar por mp_payment_id
                if (!$payment) {
                    $payment = Payment::where('mp_payment_id', $paymentId)->first();
                }
                
                // Si aún no encontramos, buscar por preference_id
                if (!$payment && isset($mpPayment->metadata->preference_id)) {
                    $payment = Payment::where('mp_preference_id', $mpPayment->metadata->preference_id)->first();
                }

                if (!$payment) {
                    Log::error('Payment not found in webhook', [
                        'payment_id' => $paymentId,
                        'external_reference' => $externalReference
                    ]);
                    return response()->json(['error' => 'Payment not found'], 404);
                }

                // Actualizar mp_payment_id si no está
                if (!$payment->mp_payment_id) {
                    $payment->mp_payment_id = $paymentId;
                }
                
                // Procesar según el estado
                switch ($mpPayment->status) {
                    case 'approved':
                        if ($payment->isPending()) {
                            $payment->markAsMpApproved([
                                'mp_payment_id' => $paymentId,
                                'status' => $mpPayment->status,
                                'status_detail' => $mpPayment->status_detail ?? null,
                                'payment_method' => $mpPayment->payment_method_id ?? null,
                                'installments' => $mpPayment->installments ?? 1,
                                'transaction_amount' => $mpPayment->transaction_amount ?? $payment->amount
                            ]);
                            
                            // Procesar lógica de negocio
                            $this->processPaymentLogic($payment);
                        }
                        break;
                        
                    case 'rejected':
                    case 'cancelled':
                        if ($payment->isPending()) {
                            $payment->markAsFailed($mpPayment->status_detail ?? 'Pago rechazado');
                        }
                        break;
                        
                    case 'pending':
                        $payment->markAsPending();
                        break;
                }

                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Invalid webhook type'], 400);

        } catch (\Exception $e) {
            Log::error('Error en webhook de Mercado Pago: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function success(Request $request)
    {
        $paymentId = $request->get('payment');
        $payment = null;
        
        if ($paymentId) {
            $payment = Payment::find($paymentId);
        }
        
        // Si no tenemos payment en la URL, buscar en sesión
        if (!$payment) {
            $sessionPaymentId = session()->get('mp_payment_id');
            if ($sessionPaymentId) {
                $payment = Payment::find($sessionPaymentId);
            }
        }
        
        // Limpiar sesión
        session()->forget([
            'mp_payment_id',
            'mp_preference_id',
            'mp_unpaid_products'
        ]);
        
        if ($payment && $payment->type === 'publication') {
            $product = $payment->product;
            if ($product) {
                return view('products.suggestions', compact('product', 'payment'));
            }
        }
        
        return view('payment.success', compact('payment'));
    }
    
    public function failure()
    {
        // Limpiar sesión
        session()->forget([
            'mp_payment_id',
            'mp_preference_id',
            'mp_unpaid_products'
        ]);
        
        return view('payment.failure');
    }
}