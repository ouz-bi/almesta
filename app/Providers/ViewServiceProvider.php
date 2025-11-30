<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = Auth::check()
                ? Cart::where('user_id', Auth::id())->sum('quantity')
                : Cart::where('session_id', session()->getId())->sum('quantity');

            $view->with('cartCount', $cartCount);
        });
    }
}
