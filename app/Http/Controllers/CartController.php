<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum('total');
        $shipping = $subtotal >= 50 ? 0 : 5.99;
        $total = $subtotal + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $quantity = $request->quantity;
        $variantId = $request->variant_id;

        // Vérifier le stock
        if ($product->has_variants && $variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            dd($variant);
            if ($variant->stock_quantity < $quantity) {
                return back()->with('error', 'Stock insuffisant pour cette variante.');
            }

            $price = $variant->price ?: $product->price;
            $productOptions = [
                'variant_id' => $variant->id,
                'size' => $variant->size ? $variant->size->name : null,
                'color' => $variant->color ? $variant->color->name : null,
                'sku' => $variant->sku
            ];
        } else {
            if ($product->stock_quantity < $quantity) {
                return back()->with('error', 'Stock insuffisant.');
            }

            $price = $product->price;
            $productOptions = null;
        }

        // Créer ou mettre à jour l'item du panier
        $cartData = [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            'product_options' => $productOptions
        ];

        if (Auth::check()) {
            $cartData['user_id'] = Auth::id();

            // Chercher si le produit existe déjà dans le panier
            $existingItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->where('product_options', $productOptions)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $quantity;
                $existingItem->save();
            } else {
                Cart::create($cartData);
            }
        } else {
            $sessionId = session()->getId();
            $cartData['session_id'] = $sessionId;

            $existingItem = Cart::where('session_id', $sessionId)
                ->where('product_id', $product->id)
                ->where('product_options', $productOptions)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $quantity;
                $existingItem->save();
            } else {
                Cart::create($cartData);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier avec succès !');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Vérifier que l'item appartient à l'utilisateur
        if (!$this->userOwnsCartItem($cart)) {
            abort(403);
        }

        // Vérifier le stock
        $product = $cart->product;
        if ($cart->product_options && isset($cart->product_options['variant_id'])) {
            $variant = ProductVariant::find($cart->product_options['variant_id']);
            if ($variant && $variant->stock_quantity < $request->quantity) {
                return back()->with('error', 'Stock insuffisant.');
            }
        } else {
            if ($product->stock_quantity < $request->quantity) {
                return back()->with('error', 'Stock insuffisant.');
            }
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return back()->with('success', 'Panier mis à jour.');
    }

    /**
     * Remove item from cart
     */
    public function remove(Cart $cart)
    {
        if (!$this->userOwnsCartItem($cart)) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Article retiré du panier.');
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            Cart::where('session_id', session()->getId())->delete();
        }

        return back()->with('success', 'Panier vidé.');
    }

    /**
     * Get cart items for current user/session
     */
    private function getCartItems()
    {
        if (Auth::check()) {
            return Cart::with(['product.category', 'product.productVariants'])
                ->where('user_id', Auth::id())
                ->get();
        }

        return Cart::with(['product.category', 'product.productVariants'])
            ->where('session_id', session()->getId())
            ->get();
    }

    /**
     * Check if user owns cart item
     */
    private function userOwnsCartItem(Cart $cart)
    {
        if (Auth::check()) {
            return $cart->user_id === Auth::id();
        }

        return $cart->session_id === session()->getId();
    }

    /**
     * Get cart count for header
     */
    public function count()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        }

        return Cart::where('session_id', session()->getId())->sum('quantity');
    }
}
