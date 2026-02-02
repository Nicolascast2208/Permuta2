<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
           $categories = Category::with(['children' => function($q){
        $q->orderBy('name', 'asc');
    }])
    ->whereNull('parent_id')
    ->orderBy('name', 'asc')
    ->get();

      
        return view('categories.index', compact('categories'));
    }

    public function show($slug)
    {
        // Buscar la categoría por slug
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Determinar si es una categoría padre o hija
        if ($category->parent_id) {
            // Es una subcategoría - filtrar productos por esta categoría
            $products = Product::where('category_id', $category->id)
                ->with(['user', 'images'])
                ->where('status', 'available')
                ->paginate(12);
                
            $parentCategory = Category::find($category->parent_id);
        } else {
            // Es una categoría padre - obtener todas sus subcategorías y productos
            $subcategories = Category::where('parent_id', $category->id)->pluck('id');
            $products = Product::whereIn('category_id', $subcategories)
                ->with(['user', 'images'])
                ->where('status', 'available')
                ->paginate(12);
                
            $parentCategory = $category;
        }
        
        // Obtener todas las categorías para el sidebar
        $categories = Category::with('children')->whereNull('parent_id')->get();
        
        return view('categories.show', compact('category', 'products', 'categories', 'parentCategory'));
    }
}