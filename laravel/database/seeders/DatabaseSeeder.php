<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Crear usuarios de prueba
   //     $users = User::factory(5)->create();

        // 2️⃣ Crear categorías con estructura jerárquica
        $categoriesData = [
            // Categorías principales con sus subcategorías
            'Accesorios Vehículos' => [
                'Repuestos',
                'Herramientas',
                'Audio',
                'Accesorios interior',
                'Accesorios exterior',
                'Llantas y rines',
            ],
            'Antigüedades y colecciones' => [
                'Juguetes antiguos',
                'Monedas y billetes',
                'Coleccionables',
                'Antigüedades',
            ],
            'Computación' => [
                'Accesorios Computación',
                'Laptops',
                'Componentes PC',
                'Periféricos',
                'Redes y conectividad',
            ],
            'Deporte y fitness' => [
                'Equipamiento Deporte',
                'Ropa y calzado Deporte',
                'Suplementos deportivos',
                'Bicicletas',
                'Accesorios Deporte',
            ],
            'Instrumentos musicales' => [
                'Guitarras',
                'Teclados y pianos',
                'Instrumentos de viento',
                'Percusión',
                'Accesorios musicales',
            ],
            'Ropa y accesorios' => [
                'Ropa para hombre',
                'Ropa para mujer',
                'Ropa niños',
                'Calzado',
                'Accesorios de moda',
            ],
            'Joyas y relojes' => [
                'Joyas',
                'Relojes',
                'Bisutería',
                'Accesorios Joyería',
            ],
            'Herramientas y construcción' => [
                'Herramientas Eléctricas',
                'Herramientas Manuales',
                'Materiales de construcción',
                'Equipos de seguridad',
            ],
            'Hogar, muebles y jardín' => [
                'Muebles',
                'Decoración',
                'Electrodomésticos',
                'Jardinería',
            ],
            'Salud y equipamiento médico' => [
                'Equipos médicos',
                'Cuidado personal',
                'Ortopedia',
                'Insumos odontología',
            ],
            'Juegos y juguetes' => [
                'Juegos de mesa',
                'Juguetes de construcción',
                'Muñecas y muñecos',
                'Vehículos de juguete',
                'Figuras coleccionables',
            ],
            'Música, películas y series' => [
                'CD\'s de música',
                'DVD\'s y Blu-rays',
                'Vinilos',
                'Series de TV',
            ],
            'Arte, librería y mercería' => [
                'Pinturas',
                'Esculturas',
                'Artículos de librería',
                'Artículos de mercería',
            ],
            'Bebés' => [
                'Ropa para bebés',
                'Coches y sillas de paseo',
                'Juguetes de bebé',
                'Muebles para bebé',
            ],
            'Belleza y cuidado personal' => [
                'Maquillaje',
                'Cuidado de la piel',
                'Cuidado del cabello',
                'Perfumes y fragancias',
            ],
            'Cámaras y accesorios' => [
                'Cámaras digitales',
                'Cámaras análogas',
                'Cámaras de video',
                'Accesorios cámaras',
                'Lentes',
            ],
            'Consolas y videojuegos' => [
                'Consolas',
                'Videojuegos',
                'Gamepads',
                'Accesorios Consolas',
            ],
            'Celulares y teléfonos' => [
                'Smartphones',
                'Teléfonos fijos',
                'Accesorios Celulares',
                'Repuestos Celulares',
            ],
            'Autos, motos y otros' => [
                'Automóviles',
                'Motos',
                'Camionetas',
                'Otros vehículos',
            ],
            'Inmuebles' => [
                'Casas',
                'Departamentos',
                'Terrenos',
                'Oficinas',
            ],
            'Libros, revistas comics' => [
                'Libros',
                'Revistas',
                'Comics',
                'Mangas',
                'Novelas gráficas',
            ],
            'Agro' => [
                'Maquinaria agrícola',
                'Equipamiento e insumos Agro',
            ],
            'Electrodomésticos' => [
                'Pequeños artefactos',
                'Electrodomésticos Cocina',
                'Aires acondicionados',
                'Calefacción',
            ],
            'Electrónica' => [
                'Televisores',
                'Equipos de audio',
                'Reproductores de video',
                'Accesorios electrónicos',
            ],
            'Entradas para eventos' => [
                'Conciertos',
                'Obras de teatro',
                'Eventos deportivos',
                'Otros eventos',
            ],
            'Industrias y oficinas' => [
                'Equipamiento para oficinas',
                'Equipamiento industrial',
                'Insumos de oficina',
                'Muebles Oficina',
            ],
            'Souvenirs, cotillón y fiestas' => [
                'Souvenirs',
                'Máscaras',
                'Disfraces',
                'Artículos de cotillón',
                'Decoración fiestas',
            ],
        ];

        // Array para trackear slugs existentes y evitar duplicados
        $existingSlugs = [];

        // Crear categorías principales y sus subcategorías
        foreach ($categoriesData as $parentName => $children) {
            // Generar slug único para la categoría padre
            $parentSlug = $this->generateUniqueSlug(Str::slug($parentName), $existingSlugs);
            $existingSlugs[] = $parentSlug;

            // Crear categoría padre
            $parent = Category::create([
                'name' => $parentName,
                'slug' => $parentSlug
            ]);

            // Crear subcategorías
            foreach ($children as $childName) {
                // Generar slug único para la subcategoría
                $childSlug = $this->generateUniqueSlug(Str::slug($childName), $existingSlugs);
                $existingSlugs[] = $childSlug;

                Category::create([
                    'name' => $childName,
                    'slug' => $childSlug,
                    'parent_id' => $parent->id
                ]);
            }
        }

        // Obtener todas las categorías (incluyendo subcategorías)
        $categories = Category::all();

        // 3️⃣ Crear productos con factory
        Product::factory(20)->make()->each(function ($product) use ($users, $categories) {
            $product->user_id = $users->random()->id;
            
            // Asignar una categoría aleatoria (puede ser principal o subcategoría)
            $product->category_id = $categories->random()->id;
            $product->save();

            ProductImage::create([
                'product_id' => $product->id,
                'path' => 'images/default-product.png',
            ]);
        });
    }

    /**
     * Genera un slug único basado en un nombre, evitando duplicados
     */
    private function generateUniqueSlug($baseSlug, &$existingSlugs)
    {
        $slug = $baseSlug;
        $counter = 1;
        
        // Mientras el slug exista en nuestro array, agregar un sufijo numérico
        while (in_array($slug, $existingSlugs)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}