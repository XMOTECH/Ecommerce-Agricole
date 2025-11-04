<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Légumes',
                'description' => 'Légumes frais cultivés localement',
                'image_url' => null,
            ],
            [
                'name' => 'Fruits',
                'description' => 'Fruits de saison et exotiques',
                'image_url' => null,
            ],
            [
                'name' => 'Céréales',
                'description' => 'Riz, maïs, mil et autres céréales',
                'image_url' => null,
            ],
            [
                'name' => 'Tubercules',
                'description' => 'Manioc, patates, ignames et autres tubercules',
                'image_url' => null,
            ],
            [
                'name' => 'Élevage',
                'description' => 'Produits d\'élevage : volailles, bovins, ovins',
                'image_url' => null,
            ],
            [
                'name' => 'Produits Laitiers',
                'description' => 'Lait, fromage, yaourt et produits dérivés',
                'image_url' => null,
            ],
            [
                'name' => 'Oléagineux',
                'description' => 'Arachides, noix de cajou, graines diverses',
                'image_url' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
