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
        // Catégories principales
        $scrubsHomme = Category::create([
            'name' => 'Scrubs Homme',
            'slug' => 'scrubs-homme',
            'description' => 'Vêtements chirurgicaux pour professionnels de santé masculins',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $scrubsFemme = Category::create([
            'name' => 'Scrubs Femme',
            'slug' => 'scrubs-femme',
            'description' => 'Vêtements chirurgicaux pour professionnelles de santé',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $scrubsHijab = Category::create([
            'name' => 'Scrubs Hijab',
            'slug' => 'scrubs-hijab',
            'description' => 'Vêtements chirurgicaux pour professionnelles voilées',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Sous-catégories pour Scrubs Homme
        Category::create([
            'name' => 'Hauts Homme',
            'slug' => 'hauts-homme',
            'parent_id' => $scrubsHomme->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Pantalons Homme',
            'slug' => 'pantalons-homme',
            'parent_id' => $scrubsHomme->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::create([
            'name' => 'Ensembles Homme',
            'slug' => 'ensembles-homme',
            'parent_id' => $scrubsHomme->id,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Category::create([
            'name' => 'Blouses Homme',
            'slug' => 'blouses-homme',
            'parent_id' => $scrubsHomme->id,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // Sous-catégories pour Scrubs Femme
        Category::create([
            'name' => 'Hauts Femme',
            'slug' => 'hauts-femme',
            'parent_id' => $scrubsFemme->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Pantalons Femme',
            'slug' => 'pantalons-femme',
            'parent_id' => $scrubsFemme->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::create([
            'name' => 'Ensembles Femme',
            'slug' => 'ensembles-femme',
            'parent_id' => $scrubsFemme->id,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Category::create([
            'name' => 'Blouses Femme',
            'slug' => 'blouses-femme',
            'parent_id' => $scrubsFemme->id,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        Category::create([
            'name' => 'Scrubs Grossesse',
            'slug' => 'scrubs-grossesse',
            'parent_id' => $scrubsFemme->id,
            'is_active' => true,
            'sort_order' => 5,
        ]);

        // Catégories supplémentaires
        Category::create([
            'name' => 'Bonnets Chirurgicaux',
            'slug' => 'bonnets-chirurgicaux',
            'description' => 'Bonnets pour opérations chirurgicales',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        Category::create([
            'name' => 'Outlet',
            'slug' => 'outlet',
            'description' => 'Produits en promotion',
            'is_active' => true,
            'sort_order' => 5,
        ]);
    }
}
