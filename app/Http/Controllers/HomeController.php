<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->active()
            ->featured()
            ->limit(8)
            ->get();
            
        $categories = Category::active()
            ->mainCategories()
            ->orderBy('sort_order')
            ->get();
            
        $bestSellers = Product::with('category')
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        return view('home', compact('featuredProducts', 'categories', 'bestSellers'));
    }
}
