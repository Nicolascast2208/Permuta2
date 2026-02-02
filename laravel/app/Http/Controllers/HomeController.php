<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache; // <--- ¡Vital! No olvides importar esto

class HomeController extends Controller
{
    public function index()
    {
        // Usamos Cache::remember.
        // 'home_page_html': Es la llave (nombre) del archivo de caché.
        // 300: Segundos de duración (5 minutos). Puedes bajarlo a 60 o 120 si prefieres.
        $html = Cache::remember('home_page_html', 300, function () {

            // --- INICIO DE LA LÓGICA PESADA (Solo ocurre una vez cada 5 mins) ---

            // 1. Productos Destacados
            $featuredProducts = Product::where('published', true)
                ->where('status', 'available')
                ->where('payment_option', 'paid')
                ->inRandomOrder() // Esto es lento, pero ahora solo corre 1 vez
                ->take(8)
                ->with('images') // Eager loading correcto
                ->get();

            // 2. Últimas Novedades
            $latestProducts = Product::where('published', true)
                ->where('status', 'available')
                ->latest()
                ->take(4)
                ->with('images')
                ->get();

            // 3. Recomendados
            $recommendedProducts = Product::where('published', true)
                ->where('status', 'available')
                ->inRandomOrder()
                ->take(8)
                ->with('images')
                ->get();

            // 4. Categorías Principales
            $mainCategories = Category::whereNull('parent_id')
                ->with('children')
                ->orderBy('name')
                ->take(16)
                ->get();

            // 5. Categorías Aleatorias
            $randomCategories = Category::whereNotNull('image')
                ->where('image', '!=', '')
                ->inRandomOrder()
                ->take(6)
                ->get();

            // --- RENDERIZADO ---
            // Aquí está el truco: Renderizamos la vista a texto HTML y retornamos eso.
            return view('home', compact(
                'featuredProducts',
                'latestProducts',
                'recommendedProducts',
                'mainCategories',
                'randomCategories'
            ))->render(); // <--- El ->render() final es clave para guardar HTML, no objetos.

        }); // --- FIN DEL CACHÉ ---

        // Devolvemos el HTML ya cocinado
        return $html;
    }
}