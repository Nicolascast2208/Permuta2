<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\OfferReceived;
use App\Events\TradeAccepted;
use App\Notifications\OfferReceivedEmail;
use App\Notifications\OfferSentEmail;
use App\Notifications\OfferAcceptedEmail;
use App\Notifications\OfferRejectedEmail;
use App\Notifications\ProductCreatedEmail;
class OfferController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $request->validate([
            'product_requested_id' => 'required|exists:products,id',
            'products_offered' => 'required|array|min:1',
            'products_offered.*' => 'exists:products,id',
            'complementary_amount' => 'nullable|numeric|min:0',
            'comment' => 'nullable|string|max:1000'
        ]);

        $productRequested = Product::find($request->product_requested_id);
        
        if ($productRequested->user_id === Auth::id()) {
            return back()->with('error', 'No puedes ofertar tu propio producto');
        }
        
        if ($productRequested->status !== 'available') {
            return back()->with('error', 'Este producto no está disponible para intercambios.');
        }
        
        $existingOffer = Offer::getUserOffer(Auth::id(), $request->product_requested_id);
        
        if ($existingOffer) {
            switch ($existingOffer->status) {
                case 'pending':
                    return back()->with('error', 'Ya tienes una oferta pendiente para este producto. Espera a que sea aceptada o rechazada.');
                case 'accepted':
                    return back()->with('error', 'Ya tienes una oferta aceptada para este producto.');
                case 'waiting_payment':
                    return back()->with('error', 'Ya tienes una oferta en proceso de pago para este producto.');
            }
        }

        foreach ($request->products_offered as $productOfferedId) {
            $product = Product::find($productOfferedId);
            if ($product->user_id !== Auth::id()) {
                return back()->with('error', 'Uno de los productos seleccionados no te pertenece');
            }
        }

        $totalOfferValue = 0;
        foreach ($request->products_offered as $productOfferedId) {
            $product = Product::find($productOfferedId);
            $totalOfferValue += $product->price_reference;
        }
        $totalOfferValue += $request->complementary_amount;
        
        $priceDifference = abs($totalOfferValue - $productRequested->price_reference);
        $pricePercentage = ($priceDifference / $productRequested->price_reference) * 100;
        
        if ($pricePercentage > 25 && !$request->has('confirm_difference')) {
            return back()->withInput()->with('warning', 'La diferencia de precio es significativa. Por favor confirma tu oferta.');
        }

        $offer = Offer::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $productRequested->user_id,
            'product_requested_id' => $request->product_requested_id,
            'complementary_amount' => $request->complementary_amount ?? 0,
            'comment' => $request->comment,
            'status' => 'pending',
            'payment_status' => 'pending',
            'read_by_receiver' => false
        ]);

        $offer->productsOffered()->attach($request->products_offered);
        event(new OfferReceived($offer));
         // Email al receptor de la oferta
        $offer->toUser->notify(new OfferReceivedEmail($offer));
        
        //  Email al que envía la oferta
        $offer->fromUser->notify(new OfferSentEmail($offer));

        return redirect()->route('offers.success', $offer);
    }

    public function accept(Offer $offer)
    {
        $this->authorize('accept', $offer);
        
        \Log::info("Iniciando aceptación de oferta ID: " . $offer->id);
        
        $offer->load(['productRequested', 'productsOffered']);
        
        \Log::info("Producto solicitado ID: " . $offer->product_requested_id);
        \Log::info("Estado del producto: " . $offer->productRequested->status);
        
        if ($offer->productRequested->status !== 'available') {
            \Log::warning("Producto no disponible para oferta ID: " . $offer->id);
            return back()->with('error', 'Este producto ya no está disponible para intercambios.');
        }
        
        // VERIFICAR SI EL PRODUCTO SOLICITADO FUE PAGADO (RECEPTOR)
        if (!$offer->productRequested->was_paid) {
            \Log::info("Producto solicitado no pagado - redirigiendo a pago del receptor");
            return redirect()->route('checkout.commission', $offer);
        }
        
        // VERIFICAR SI LOS PRODUCTOS OFRECIDOS FUERON PAGADOS (OFERTANTE)
        $unpaidOfferedProducts = $offer->productsOffered->filter(function($product) {
            return !$product->was_paid;
        });
        
        if ($unpaidOfferedProducts->count() > 0) {
            \Log::info("Productos ofrecidos no pagados - cambiando estado a waiting_payment");
            
            try {
                DB::transaction(function () use ($offer) {
                    // Poner la oferta en estado de espera de pago del OFERTANTE
                    $offer->update(['status' => 'waiting_payment']);
                    
                    // RECHAZAR AUTOMÁTICAMENTE TODAS LAS DEMÁS OFERTAS PENDIENTES PARA ESTE PRODUCTO
                    Offer::where('product_requested_id', $offer->product_requested_id)
                        ->where('id', '!=', $offer->id)
                        ->whereIn('status', ['pending'])
                        ->update([
                            'status' => 'rejected',
                            'updated_at' => now()
                        ]);
                    
                    \Log::info("Oferta ID: " . $offer->id . " puesta en waiting_payment - esperando pago del ofertante");
                });
                
                return back()->with('info', 'Oferta aceptada. Ahora el ofertante debe pagar la comisión para completar el trueque.');
                
            } catch (\Exception $e) {
                \Log::error("Error en transacción: " . $e->getMessage());
                return back()->with('error', 'Hubo un error al procesar la oferta. Por favor intenta nuevamente.');
            }
        }
        
        // SI TODOS LOS PAGOS ESTÁN COMPLETOS, ACEPTAR LA OFERTA COMPLETAMENTE
        try {
            DB::transaction(function () use ($offer) {
                \Log::info("Iniciando transacción para aceptación completa de oferta ID: " . $offer->id);
                
                // RECHAZAR AUTOMÁTICAMENTE TODAS LAS DEMÁS OFERTAS PENDIENTES PARA ESTE PRODUCTO
                $rejectedCount = Offer::where('product_requested_id', $offer->product_requested_id)
                    ->where('id', '!=', $offer->id)
                    ->whereIn('status', ['pending', 'waiting_payment'])
                    ->update([
                        'status' => 'rejected',
                        'updated_at' => now()
                    ]);
                
                \Log::info("Ofertas rechazadas: " . $rejectedCount);
                
                // ACEPTAR LA OFERTA ACTUAL
                $offer->update(['status' => 'accepted']);
                \Log::info("Oferta actual aceptada: " . $offer->id);
                
                // Actualizar estado de los productos
                $offer->productRequested->update(['status' => 'paired']);
                \Log::info("Producto solicitado actualizado a paired: " . $offer->productRequested->id);
                
                // Actualizar estado de todos los productos ofrecidos
                foreach ($offer->productsOffered as $productOffered) {
                    $productOffered->update(['status' => 'paired']);
                    \Log::info("Producto ofrecido actualizado a paired: " . $productOffered->id);
                }
                
                // Crear chat si no existe
                if (!$offer->chat) {
                    $chat = Chat::create([
                        'offer_id' => $offer->id,
                        'user1_id' => $offer->from_user_id,
                        'user2_id' => $offer->to_user_id
                    ]);
                    \Log::info("Chat creado ID: " . $chat->id);
                }
            });
            
            \Log::info("Transacción completada exitosamente para oferta ID: " . $offer->id);
            
        } catch (\Exception $e) {
            \Log::error("Error en transacción: " . $e->getMessage());
            return back()->with('error', 'Hubo un error al procesar la oferta. Por favor intenta nuevamente.');
        }
        
        event(new TradeAccepted($offer));
        //  Email al ofertante
        $offer->fromUser->notify(new OfferAcceptedEmail($offer));
        
        //Email al receptor 
      //  $offer->toUser->notify(new OfferAcceptedEmail($offer));
        return back()->with('success', 'Oferta aceptada correctamente. Las demás ofertas para este producto han sido rechazadas automáticamente. Se ha creado un chat para coordinar el trueque.');
    }

    public function reject(Offer $offer)
    {
        $this->authorize('reject', $offer);
        
        $offer->update(['status' => 'rejected']);
         // NUEVO: Email al ofertante
        $offer->fromUser->notify(new OfferRejectedEmail($offer));
        return back()->with('success', 'Oferta rechazada');
    }

    public function create(Product $product)
    {
        $existingOffer = Offer::getUserOffer(auth()->id(), $product->id);
        
        if ($existingOffer) {
            return redirect()->route('products.show', $product)
                ->with('error', 'Ya tienes una oferta ' . $existingOffer->status_name . ' para este producto.');
        }

        if (auth()->id() === $product->user_id) {
            return redirect()->route('products.show', $product)
                ->with('error', 'No puedes ofertar tu propio producto');
        }

        $myProducts = auth()->user()->products()
            ->where('status', 'available')
            ->with('images')
            ->get();
        
        return view('offers.create', compact('product', 'myProducts'));
    }

    public function intermediate(Offer $offer)
    {
        $this->authorize('view', $offer);
        
        $offer->load([
            'productsOffered.images',
            'productRequested.images', 
            'fromUser', 
            'toUser', 
            'intermediateQuestions.user', 
            'intermediateQuestions.answerer'
        ]);
        
        return view('offers.intermediate', compact('offer'));
    }

    public function success(Offer $offer)
    {
        if ($offer->from_user_id !== auth()->id() && $offer->to_user_id !== auth()->id()) {
            abort(403);
        }

        return view('offers.success', compact('offer'));
    }
}