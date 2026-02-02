<?php
// app/Http/Controllers/Admin/PaymentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'product', 'offer']);

        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('buy_order', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('authorization_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $payments = $query->latest()->paginate(20);
        $users = User::all();
        $stats = $this->getPaymentStats();

        return view('admin.payments.index', compact('payments', 'users', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'product.images', 'offer.productsOffered', 'offer.productRequested']);
        return view('admin.payments.show', compact('payment'));
    }

    private function getPaymentStats()
    {
        return [
            'total' => Payment::count(),
            'approved' => Payment::approved()->count(),
            'pending' => Payment::pending()->count(),
            'failed' => Payment::failed()->count(),
            'total_amount' => Payment::approved()->sum('amount'),
            'today_count' => Payment::whereDate('created_at', today())->count(),
            'today_amount' => Payment::approved()->whereDate('created_at', today())->sum('amount'),
        ];
    }

    public function byUser(User $user)
    {
        $payments = Payment::with(['product', 'offer'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Payment::where('user_id', $user->id)->count(),
            'approved' => Payment::where('user_id', $user->id)->approved()->count(),
            'total_amount' => Payment::where('user_id', $user->id)->approved()->sum('amount'),
        ];

        return view('admin.payments.user', compact('payments', 'user', 'stats'));
    }
}