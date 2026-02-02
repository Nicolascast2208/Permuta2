<?php
// app/Http/Controllers/Admin/OfferController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::with([
            'fromUser', 
            'toUser', 
             'productsOffered.images', 
            'productRequested.images'
        ]);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('fromUser', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('toUser', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $offers = $query->latest()->paginate(20);

        return view('admin.offers.index', compact('offers'));
    }

    public function show(Offer $offer)
    {
        // Cargar todas las relaciones necesarias usando productsOffered
        $offer->load([
            'fromUser', 
            'toUser', 
            'productsOffered.images', // Relación de múltiples productos
            'productRequested.images',
            'chat.messages.user',
            'intermediateQuestions.user'
        ]);

        return view('admin.offers.show', compact('offer'));
    }

    public function accept(Offer $offer)
    {
        $offer->update(['status' => 'accepted']);

        // Aquí podrías agregar lógica adicional como notificaciones, etc.

        return redirect()->back()->with('success', 'Oferta aceptada correctamente.');
    }

    public function reject(Offer $offer)
    {
        $offer->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Oferta rechazada correctamente.');
    }

    public function updatePaymentStatus(Request $request, Offer $offer)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid'
        ]);

        $offer->update(['payment_status' => $request->payment_status]);

        return redirect()->back()->with('success', 'Estado de pago actualizado correctamente.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'Oferta eliminada correctamente.');
    }
}