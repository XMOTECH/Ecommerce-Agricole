<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Légumes
            [
                'name' => 'Tomates Bio',
                'description' => 'Tomates rouges mûres cultivées sans pesticides',
                'price' => 2500,
                'stock_quantity' => 100,
                'unit' => 'kg',
                'category_id' => 1,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Oignons Rouges',
                'description' => 'Oignons rouges frais de qualité supérieure',
                'price' => 1800,
                'stock_quantity' => 80,
                'unit' => 'kg',
                'category_id' => 1,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Carottes',
                'description' => 'Carottes croquantes et sucrées',
                'price' => 2000,
                'stock_quantity' => 60,
                'unit' => 'kg',
                'category_id' => 1,
                'image_url' => null,
                'is_active' => true,
            ],

            // Fruits
            [
                'name' => 'Oranges Valencia',
                'description' => 'Oranges juteuses et sucrées',
                'price' => 3000,
                'stock_quantity' => 50,
                'unit' => 'kg',
                'category_id' => 2,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Mangues Kent',
                'description' => 'Mangues savoureuses de saison',
                'price' => 4000,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'category_id' => 2,
                'image_url' => null,
                'is_active' => true,
            ],

            // Céréales
            [
                'name' => 'Riz Basmati',
                'description' => 'Riz parfumé de qualité premium',
                'price' => 5000,
                'stock_quantity' => 200,
                'unit' => 'kg',
                'category_id' => 3,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Maïs Blanc',
                'description' => 'Maïs blanc tendre et savoureux',
                'price' => 2200,
                'stock_quantity' => 150,
                'unit' => 'kg',
                'category_id' => 3,
                'image_url' => null,
                'is_active' => true,
            ],

            // Élevage
            [
                'name' => 'Poulets de Chair',
                'description' => 'Poulets élevés en liberté, prêts à cuire',
                'price' => 15000,
                'stock_quantity' => 20,
                'unit' => 'pièce',
                'category_id' => 5,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Œufs de Poule',
                'description' => 'Œufs frais de poules élevées au grain',
                'price' => 800,
                'stock_quantity' => 100,
                'unit' => 'pièce',
                'category_id' => 5,
                'image_url' => null,
                'is_active' => true,
            ],

            // Produits Laitiers
            [
                'name' => 'Lait Cru',
                'description' => 'Lait frais non pasteurisé',
                'price' => 2500,
                'stock_quantity' => 40,
                'unit' => 'litre',
                'category_id' => 6,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Fromage Local',
                'description' => 'Fromage artisanal au lait de vache',
                'price' => 12000,
                'stock_quantity' => 15,
                'unit' => 'kg',
                'category_id' => 6,
                'image_url' => null,
                'is_active' => true,
            ],

            // Oléagineux
            [
                'name' => 'Arachides Grillées',
                'description' => 'Arachides grillées et salées',
                'price' => 3500,
                'stock_quantity' => 70,
                'unit' => 'kg',
                'category_id' => 7,
                'image_url' => null,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
