<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['name' => 'XS', 'label' => 'Extra Small', 'category' => 'clothing', 'sort_order' => 1],
            ['name' => 'S', 'label' => 'Small', 'category' => 'clothing', 'sort_order' => 2],
            ['name' => 'M', 'label' => 'Medium', 'category' => 'clothing', 'sort_order' => 3],
            ['name' => 'L', 'label' => 'Large', 'category' => 'clothing', 'sort_order' => 4],
            ['name' => 'XL', 'label' => 'Extra Large', 'category' => 'clothing', 'sort_order' => 5],
            ['name' => 'XXL', 'label' => 'Double Extra Large', 'category' => 'clothing', 'sort_order' => 6],
            
            // Tailles chaussures
            ['name' => '36', 'label' => 'Pointure 36', 'category' => 'shoes', 'sort_order' => 36],
            ['name' => '37', 'label' => 'Pointure 37', 'category' => 'shoes', 'sort_order' => 37],
            ['name' => '38', 'label' => 'Pointure 38', 'category' => 'shoes', 'sort_order' => 38],
            ['name' => '39', 'label' => 'Pointure 39', 'category' => 'shoes', 'sort_order' => 39],
            ['name' => '40', 'label' => 'Pointure 40', 'category' => 'shoes', 'sort_order' => 40],
            ['name' => '41', 'label' => 'Pointure 41', 'category' => 'shoes', 'sort_order' => 41],
            ['name' => '42', 'label' => 'Pointure 42', 'category' => 'shoes', 'sort_order' => 42],
            ['name' => '43', 'label' => 'Pointure 43', 'category' => 'shoes', 'sort_order' => 43],
            ['name' => '44', 'label' => 'Pointure 44', 'category' => 'shoes', 'sort_order' => 44],
            ['name' => '45', 'label' => 'Pointure 45', 'category' => 'shoes', 'sort_order' => 45],
        ];
        
        foreach ($sizes as $size) {
            \App\Models\Size::create($size);
        }
    }
}
