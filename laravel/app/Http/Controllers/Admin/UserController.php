<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['products', 'reviews', 'sentOffers', 'receivedOffers']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('alias', 'like', "%{$search}%")
                  ->orWhere('rut', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount(['products', 'reviews', 'sentOffers', 'receivedOffers']);
        
        $products = $user->products()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(10, ['*'], 'products_page');
            
        $reviews = $user->reviews()
            ->with('author')
            ->latest()
            ->paginate(10, ['*'], 'reviews_page');
            
        $sentOffers = $user->sentOffers()
            ->with(['productRequested', 'toUser'])
            ->latest()
            ->paginate(10, ['*'], 'sent_offers_page');
            
        $receivedOffers = $user->receivedOffers()
            ->with(['productOffered', 'fromUser'])
            ->latest()
            ->paginate(10, ['*'], 'received_offers_page');

        return view('admin.users.show', compact(
            'user', 
            'products', 
            'reviews', 
            'sentOffers', 
            'receivedOffers'
        ));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function deactivate(Request $request, User $user)
    {
        $request->validate([
            'deactivation_reason' => 'required|string|max:500'
        ]);

        // No permitir desactivarse a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $user->deactivate($request->deactivation_reason);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuario desactivado correctamente. Todos sus productos han sido ocultados.');
    }

    public function activate(User $user)
    {
        $user->activate();

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuario activado correctamente. Sus productos han sido reactivados.');
    }

    public function toggleStatus(User $user)
    {
        // No permitir cambiar estado a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes cambiar el estado de tu propio usuario.');
        }

        if ($user->is_active) {
            $user->deactivate('Cambio de estado desde el admin');
            $message = 'Usuario desactivado correctamente.';
        } else {
            $user->activate();
            $message = 'Usuario activado correctamente.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}