<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // Lista de títulos realistas
        $titles = [
            'Celular iPhone 16 XS',
            'Camioneta Ford 2016',
            'Computador HP 55xx',
            'Bicicleta Trek 2020',
            'Consola PlayStation 5',
            'Tablet Samsung Galaxy Tab',
            'Auto Suzuki Swift 2018',
            'Instrumento Musical Yamaha',
            'Patines Rollerblade',
            'Electrodoméstico LG'
        ];

        // Lista de tags asociados
        $tags = [
            'Celulares',
            'Autos',
            'Computadores',
            'Bicicletas',
            'Consolas',
            'Tablets',
            'Instrumentos musicales',
            'Patines',
            'Electrodomésticos',
            'Muebles'
        ];

        // Ciudades con coordenadas (Chile)
 $locations = [
    ['location' => 'Providencia',        'lat' => -33.4378, 'lng' => -70.6347],
    ['location' => 'Santiago Centro',    'lat' => -33.4372, 'lng' => -70.6506],
    ['location' => 'Las Condes',         'lat' => -33.3999, 'lng' => -70.5797],
    ['location' => 'Vitacura',           'lat' => -33.3907, 'lng' => -70.5851],
    ['location' => 'Ñuñoa',              'lat' => -33.4623, 'lng' => -70.6140],
    ['location' => 'Maipú',              'lat' => -33.4940, 'lng' => -70.7315],
    ['location' => 'La Florida',         'lat' => -33.5173, 'lng' => -70.5792],
    ['location' => 'Puente Alto',        'lat' => -33.6111, 'lng' => -70.5750],
    ['location' => 'Valparaíso',         'lat' => -33.0472, 'lng' => -71.6127],
    ['location' => 'Viña del Mar',       'lat' => -33.0245, 'lng' => -71.5515],
    ['location' => 'Concepción',         'lat' => -36.8201, 'lng' => -73.0444],
    ['location' => 'Talcahuano',         'lat' => -36.7167, 'lng' => -73.1167],
    ['location' => 'La Serena',          'lat' => -29.9027, 'lng' => -71.2520],
    ['location' => 'Coquimbo',           'lat' => -29.9530, 'lng' => -71.3396],
    ['location' => 'Antofagasta',        'lat' => -23.6509, 'lng' => -70.3975],
    ['location' => 'Puerto Montt',       'lat' => -41.4693, 'lng' => -72.9424],
    ['location' => 'Temuco',             'lat' => -38.7359, 'lng' => -72.5904],
    ['location' => 'Iquique',            'lat' => -20.2133, 'lng' => -70.1503],
];


        $index = array_rand($titles);
        $title = $titles[$index];
        $tag = $tags[$index]; // coincidir tag con título
        $city = $locations[array_rand($locations)];

        return [
            'title' => $title,
            'price_reference' => $this->faker->numberBetween(10000, 500000),
            'description' => $this->faker->paragraph(),
            'publication_date' => Carbon::now(),
            'expiration_date' => Carbon::now()->addDays(30),
            'condition' => $this->faker->randomElement(['new', 'used']),
            'tags' => $tag,
            'status' => 'available',
            'published' => true,
            'location' => $city['location'],
            'payment_option' => $this->faker->randomElement(['free']),
            'was_paid' => false,
            'latitude' => $city['lat'],
            'longitude' => $city['lng'],
        ];
    }
}
