<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
public function index()
{
    // Obtener solo usuarios activos que no sean administradores
    $users = User::withCount(['products', 'reviews'])
        ->where('role', '!=', 'admin')
        ->where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    return view('users.index', compact('users'));
}
   
    public function showProfile(User $user)
    {
        // Obtener productos publicados por el usuario
        $products = $user->products()
            ->with('images')
            ->where('status', 'available')
            ->latest()
            ->get();

        // Obtener reseñas recibidas por el usuario
        $reviews = $user->reviews()
            ->with('author')
            ->latest()
            ->get();

        // Contar permutas completadas (productos con status 'paired')
        $completedSwaps = $user->products()
            ->where('status', 'paired')
            ->count();

        return view('user.profile', [
            'user' => $user,
            'products' => $products,
            'reviews' => $reviews,
            'completedSwaps' => $completedSwaps // Nueva variable
        ]);
    }

    public function show(User $user)
    {
        $products = $user->products()->paginate(1); // Paginación de productos
        $reviews = $user->reviews()->paginate(2);   // Paginación de reseñas
        
        return view('profile.show', compact('user', 'products', 'reviews'));
    }
}