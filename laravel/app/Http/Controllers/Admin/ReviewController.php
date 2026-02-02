<?php
// app/Http/Controllers/Admin/ReviewController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['author', 'reviewedUser']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('author', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('reviewedUser', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['author', 'reviewedUser']);
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        // Aquí podrías agregar lógica para aprobar reseñas si es necesario
        return redirect()->back()->with('success', 'Reseña aprobada correctamente.');
    }

    public function reject(Review $review)
    {
        // Aquí podrías agregar lógica para rechazar reseñas si es necesario
        return redirect()->back()->with('success', 'Reseña rechazada correctamente.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Reseña eliminada correctamente.');
    }
}