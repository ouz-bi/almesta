<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home - Page d'accueil Almesta
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes produits
Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{slug}', [ProductController::class, 'show'])->name('products.show');

// Routes panier
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{cart}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/panier', [CartController::class, 'clear'])->name('cart.clear');

// Routes wishlist (authentification requise)

// Dashboard (from Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes (for future authentication)
Route::middleware(['auth'])->group(function () {
    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/favoris', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/favoris/ajouter/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/favoris/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/favoris/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

require __DIR__.'/auth.php';
