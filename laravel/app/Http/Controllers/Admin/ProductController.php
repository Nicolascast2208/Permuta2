<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['user', 'category', 'images']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('published') && $request->published !== '') {
            $query->where('published', $request->published);
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category', 'images', 'questions.user', 'questions.answers.user']);
        $categories = Category::all();
        
        return view('admin.products.show', compact('product', 'categories'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price_reference' => 'required|numeric|min:0',
            'condition' => 'required|in:new,used,refurbished',
            'status' => 'required|in:pending,available,paired',
            'published' => 'boolean',
            'tags' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function approve(Product $product)
    {
        $product->update([
            'status' => 'available',
            'published' => true
        ]);

        return redirect()->back()->with('success', 'Producto aprobado correctamente.');
    }

    public function reject(Product $product)
    {
        $product->update([
            'status' => 'pending',
            'published' => false
        ]);

        return redirect()->back()->with('success', 'Producto rechazado correctamente.');
    }

    public function togglePublished(Product $product)
    {
        $product->update([
            'published' => !$product->published
        ]);

        $status = $product->published ? 'publicado' : 'oculto';
        return redirect()->back()->with('success', "Producto {$status} correctamente.");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}