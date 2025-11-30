<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'productVariants.size', 'productVariants.color'])->active();

        // Filtrage par catégorie
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrage par genre
        if ($request->has('gender') && $request->gender !== '') {
            $query->byGender($request->gender);
        }

        // Filtrage par recherche
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Filtrage par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtrage par promotion
        if ($request->has('promotion') && $request->promotion == 1) {
            $query->whereNotNull('compare_price')
                  ->whereColumn('compare_price', '>', 'price');
        }

        // Filtrage par taille
        if ($request->has('size') && $request->size !== '') {
            $query->whereHas('productVariants', function($q) use ($request) {
                $q->where('size_id', $request->size)
                  ->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            });
        }

        // Filtrage par couleur
        if ($request->has('color') && $request->color !== '') {
            $query->whereHas('productVariants', function($q) use ($request) {
                $q->where('color_id', $request->color)
                  ->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            });
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->mainCategories()->orderBy('sort_order')->get();

        // Pour les filtres de taille et couleur
        $sizes = \App\Models\Size::active()->ordered()->get();
        $colors = \App\Models\Color::active()->ordered()->get();

        return view('products.index', compact('products', 'categories', 'sizes', 'colors'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with([
            'category',
            'productVariants.size',
            'productVariants.color',
            'productVariants' => function($query) {
                $query->active()->orderBy('stock_quantity', 'desc');
            }
        ])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Produits similaires
        $relatedProducts = Product::with(['category', 'productVariants'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
