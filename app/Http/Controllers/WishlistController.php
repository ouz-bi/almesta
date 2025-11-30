<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the wishlist
     */
    public function index()
    {
        $favorites = Favorite::with(['product.category', 'product.productVariants'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wishlist.index', compact('favorites'));
    }

    /**
     * Add product to wishlist
     */
    public function add(Product $product)
    {
        $exists = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Ce produit est déjà dans vos favoris.');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id
        ]);

        return back()->with('success', 'Produit ajouté aux favoris !');
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Product $product)
    {
        Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Produit retiré des favoris.');
    }

    /**
     * Toggle product in wishlist
     */
    public function toggle(Product $product)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Produit retiré des favoris'
            ]);
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Produit ajouté aux favoris'
        ]);
    }
}
