<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // AJOUTEZ CETTE PARTIE - Fusionner le panier
        $this->mergeGuestCart($request);

        return redirect(route('dashboard', absolute: false));
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
            // Transférer directement (nouvel utilisateur = panier vide)
            $guestItem->user_id = $user->id;
            $guestItem->session_id = null;
            $guestItem->save();
        }
    }
}
