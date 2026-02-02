<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function results(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'want');
        
        // Validar que haya término de búsqueda
        if (empty($query)) {
            return redirect()->back()->with('error', 'Por favor ingresa un término de búsqueda');
        }
        
        // Construir la consulta según el tipo de búsqueda
        $products = Product::query()
            ->with(['category', 'images'])
            ->where('status', 'available')
            ->where('published', true);
        
        if ($type === 'want') {
            // Buscar por nombre del producto O por categoría
            $products->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhereHas('category', function ($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'like', '%' . $query . '%');
                  });
            });
        } else {
            // Buscar por tags (etiquetas)
            $products->where('tags', 'like', '%' . $query . '%');
        }
        
        $products = $products->paginate(12);
        
        return view('search.results', [
            'products' => $products,
            'query' => $query,
            'type' => $type
        ]);
    }
}