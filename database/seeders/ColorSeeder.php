<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Noir', 'hex_code' => '#000000', 'slug' => 'noir', 'sort_order' => 1],
            ['name' => 'Blanc', 'hex_code' => '#FFFFFF', 'slug' => 'blanc', 'sort_order' => 2],
            ['name' => 'Gris', 'hex_code' => '#808080', 'slug' => 'gris', 'sort_order' => 3],
            ['name' => 'Bleu Marine', 'hex_code' => '#000080', 'slug' => 'bleu-marine', 'sort_order' => 4],
            ['name' => 'Bleu', 'hex_code' => '#0066CC', 'slug' => 'bleu', 'sort_order' => 5],
            ['name' => 'Rouge', 'hex_code' => '#FF0000', 'slug' => 'rouge', 'sort_order' => 6],
            ['name' => 'Vert', 'hex_code' => '#008000', 'slug' => 'vert', 'sort_order' => 7],
            ['name' => 'Jaune', 'hex_code' => '#FFFF00', 'slug' => 'jaune', 'sort_order' => 8],
            ['name' => 'Rose', 'hex_code' => '#FFC0CB', 'slug' => 'rose', 'sort_order' => 9],
            ['name' => 'Violet', 'hex_code' => '#800080', 'slug' => 'violet', 'sort_order' => 10],
            ['name' => 'Orange', 'hex_code' => '#FFA500', 'slug' => 'orange', 'sort_order' => 11],
            ['name' => 'Marron', 'hex_code' => '#964B00', 'slug' => 'marron', 'sort_order' => 12],
            ['name' => 'Beige', 'hex_code' => '#F5F5DC', 'slug' => 'beige', 'sort_order' => 13],
            ['name' => 'Kaki', 'hex_code' => '#C3B091', 'slug' => 'kaki', 'sort_order' => 14],
        ];
        
        foreach ($colors as $color) {
            \App\Models\Color::create($color);
        }
    }
}
