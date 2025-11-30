<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        $cartItems = Cart::with(['product.category', 'product.productVariants'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $shippingAddresses = Auth::user()->shippingAddresses;
        $billingAddresses = Auth::user()->billingAddresses;

        $subtotal = $cartItems->sum('total');
        $shipping = $subtotal >= 50 ? 0 : 5.99;
        $tax = $subtotal * 0.20; // TVA 20%
        $total = $subtotal + $shipping;

        return view('checkout.index', compact(
            'cartItems',
            'shippingAddresses',
            'billingAddresses',
            'subtotal',
            'shipping',
            'tax',
            'total'
        ));
    }

    /**
     * Store shipping address
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:shipping,billing',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'is_default' => 'boolean'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $request->boolean('is_default');

        $address = UserAddress::create($validated);

        return back()->with('success', 'Adresse ajoutée avec succès !');
    }

    /**
     * Process the order
     */
    public function processOrder(Request $request)
    {
        dd($request->all());
        $request->validate([
            'shipping_address_id' => 'required|exists:user_addresses,id',
            'billing_address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:card,paypal,bank_transfer',
            'same_as_shipping' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Vérifier que les adresses appartiennent à l'utilisateur
        $shippingAddress = UserAddress::where('id', $request->shipping_address_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $billingAddress = UserAddress::where('id', $request->billing_address_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        try {
            DB::beginTransaction();

            // Calculer les montants
            $subtotal = $cartItems->sum('total');
            $shippingAmount = $subtotal >= 50 ? 0 : 5.99;
            $taxAmount = $subtotal * 0.20;
            $totalAmount = $subtotal + $shippingAmount;

            // Créer la commande
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'shipping_address' => [
                    'first_name' => $shippingAddress->first_name,
                    'last_name' => $shippingAddress->last_name,
                    'phone' => $shippingAddress->phone,
                    'address_line1' => $shippingAddress->address_line1,
                    'address_line2' => $shippingAddress->address_line2,
                    'city' => $shippingAddress->city,
                    'state' => $shippingAddress->state,
                    'postal_code' => $shippingAddress->postal_code,
                    'country' => $shippingAddress->country,
                ],
                'billing_address' => [
                    'first_name' => $billingAddress->first_name,
                    'last_name' => $billingAddress->last_name,
                    'phone' => $billingAddress->phone,
                    'address_line1' => $billingAddress->address_line1,
                    'address_line2' => $billingAddress->address_line2,
                    'city' => $billingAddress->city,
                    'state' => $billingAddress->state,
                    'postal_code' => $billingAddress->postal_code,
                    'country' => $billingAddress->country,
                ],
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes
            ]);

            // Créer les items de commande et vérifier le stock
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // Vérifier le stock
                if ($cartItem->product_options && isset($cartItem->product_options['variant_id'])) {
                    $variant = ProductVariant::find($cartItem->product_options['variant_id']);

                    if (!$variant || $variant->stock_quantity < $cartItem->quantity) {
                        throw new \Exception("Stock insuffisant pour {$product->name}");
                    }

                    // Déduire le stock
                    $variant->decrement('stock_quantity', $cartItem->quantity);
                    $sku = $variant->sku;
                } else {
                    if ($product->stock_quantity < $cartItem->quantity) {
                        throw new \Exception("Stock insuffisant pour {$product->name}");
                    }

                    // Déduire le stock
                    $product->decrement('stock_quantity', $cartItem->quantity);
                    $sku = $product->sku;
                }

                // Créer l'item de commande
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $sku,
                    'product_options' => $cartItem->product_options,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'total_price' => $cartItem->total
                ]);
            }

            // Ajouter des points de fidélité (1 point par euro dépensé)
            $pointsEarned = floor($totalAmount);
            Auth::user()->addPoints($pointsEarned);
            Auth::user()->increment('total_spent', $totalAmount);

            // Vider le panier
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // Rediriger vers la page de confirmation
            return redirect()->route('checkout.confirmation', $order->id)
                ->with('success', 'Commande passée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création de la commande : ' . $e->getMessage());
        }
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('checkout.confirmation', compact('order'));
    }
}
