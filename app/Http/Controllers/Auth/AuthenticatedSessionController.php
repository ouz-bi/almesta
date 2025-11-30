<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Cart;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // AJOUTEZ CETTE PARTIE - Fusionner le panier
        $this->mergeGuestCart($request);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Fusionner le panier invité avec le panier utilisateur
     */
    protected function mergeGuestCart(Request $request): void
    {
        $sessionId = $request->session()->getId();
        $user = Auth::user();

        // Récupérer les items du panier invité
        $guestCartItems = Cart::where('session_id', $sessionId)->get();

        foreach ($guestCartItems as $guestItem) {
            // Vérifier si l'utilisateur a déjà cet article
            $existingItem = Cart::where('user_id', $user->id)
                ->where('product_id', $guestItem->product_id)
                ->where(function($query) use ($guestItem) {
                    if ($guestItem->product_options) {
                        $query->where('product_options', $guestItem->product_options);
                    } else {
                        $query->whereNull('product_options');
                    }
                })
                ->first();

            if ($existingItem) {
                // Additionner les quantités
                $existingItem->quantity += $guestItem->quantity;
                $existingItem->save();
                $guestItem->delete();
            } else {
                // Transférer l'item
                $guestItem->user_id = $user->id;
                $guestItem->session_id = null;
                $guestItem->save();
            }
        }
    }
}
