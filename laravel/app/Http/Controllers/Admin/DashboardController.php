<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Chat;
use App\Models\Review;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('status', 'pending')->count(),
            'pending_offers' => Offer::where('status', 'pending')->count(),
            'total_offers' => Offer::count(),
            'open_chats' => Chat::where('is_closed', false)->count(),
            'completed_trades' => Product::where('status', 'paired')->count(),
            'pending_reviews' => Review::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_products_today' => Product::whereDate('created_at', today())->count(),
            'total_payments' => Payment::count(),
        'approved_payments' => Payment::approved()->count(),
        'pending_payments' => Payment::pending()->count(),
        'total_revenue' => Payment::approved()->sum('amount'),
        'today_revenue' => Payment::approved()->whereDate('created_at', today())->sum('amount'),
        ];

        $recentActivities = $this->getRecentActivities();
        $recentUsers = User::latest()->take(5)->get();
        $recentProducts = Product::with(['user', 'images'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentActivities', 'recentUsers', 'recentProducts'));
    }

    private function getRecentActivities()
    {
        $activities = collect();
        
        // Últimos usuarios registrados
        $activities = $activities->merge(
            User::latest()->take(3)->get()->map(function($user) {
                return [
                    'type' => 'user_registered',
                    'title' => $user->name,
                    'description' => 'Nuevo usuario registrado:',
                    'time' => $user->created_at,
                    'icon' => 'user-plus',
                    'color' => 'bg-blue-500'
                ];
            })
        );

        // Últimos productos publicados
        $activities = $activities->merge(
            Product::with('user')->latest()->take(3)->get()->map(function($product) {
                return [
                    'type' => 'product_created',
                    'title' => Str::limit($product->title, 20),
                    'description' => 'Nuevo producto publicado por',
                    'time' => $product->created_at,
                    'icon' => 'shopping-bag',
                    'color' => 'bg-green-500'
                ];
            })
        );

        // Últimas ofertas
        $activities = $activities->merge(
            Offer::with(['fromUser', 'productRequested'])
                ->latest()
                ->take(2)
                ->get()
                ->map(function($offer) {
                    return [
                        'type' => 'offer_created',
                        'title' => $offer->fromUser->name,
                        'description' => 'Nueva oferta realizada por',
                        'time' => $offer->created_at,
                        'icon' => 'exchange-alt',
                        'color' => 'bg-yellow-500'
                    ];
                })
        );

        // Últimas reseñas
        $activities = $activities->merge(
            Review::with(['author', 'reviewedUser'])
                ->latest()
                ->take(2)
                ->get()
                ->map(function($review) {
                    return [
                        'type' => 'review_created',
                        'title' => $review->author->name,
                        'description' => 'Nueva reseña de',
                        'time' => $review->created_at,
                        'icon' => 'star',
                        'color' => 'bg-purple-500'
                    ];
                })
        );

        return $activities->sortByDesc('time')->take(8);
    }
}