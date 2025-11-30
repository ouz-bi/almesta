@extends('layouts.app')

@section('title', 'Mes Favoris')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">Mes Favoris</li>
            </ol>
        </nav>

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mes Favoris</h1>
                <p class="text-gray-600 mt-1">{{ $favorites->count() }} produit(s) dans votre liste de favoris</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-6">
                {{ session('info') }}
            </div>
        @endif

        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($favorites as $favorite)
                    @php
                        $product = $favorite->product;
                    @endphp
                    <div class="bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-lg transition-shadow">
                        <div class="relative">
                            <a href="{{ route('products.show', $product->slug) }}">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image['path']) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-64 object-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                                         alt="{{ $product->name }}"
                                         class="w-full h-64 object-cover">
                                @endif
                            </a>

                            @if($product->is_on_sale)
                                <div class="absolute top-3 left-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    -{{ $product->discount_percentage }}%
                                </div>
                            @endif

                            <!-- Remove from Wishlist -->
                            <div class="absolute top-3 right-3">
                                <form action="{{ route('wishlist.remove', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-white p-2 rounded-full shadow-md hover:bg-red-50 transition">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Stock Badge -->
                            @if($product->total_stock <= 0)
                                <div class="absolute bottom-3 left-3 bg-gray-900 bg-opacity-75 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Rupture de stock
                                </div>
                            @elseif($product->total_stock <= 5)
                                <div class="absolute bottom-3 left-3 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Stock limité
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <h3 class="font-semibold text-gray-900 mb-2 hover:text-primary-600 transition line-clamp-2">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <p class="text-sm text-gray-600 mb-3">
                                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-primary-600">
                                    {{ $product->category->name }}
                                </a>
                            </p>

                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    @if($product->is_on_sale)
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg font-bold text-red-600">{{ number_format($product->price, 2) }} €</span>
                                            <span class="text-sm text-gray-500 line-through">{{ number_format($product->compare_price, 2) }} €</span>
                                        </div>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 2) }} €</span>
                                    @endif
                                    <p class="text-xs text-gray-500">TTC</p>
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            @if($product->total_stock > 0)
                                @if($product->has_variants)
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center px-4 py-2 rounded-lg text-sm font-medium transition">
                                        Choisir les options
                                    </a>
                                @else
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5H20"></path>
                                            </svg>
                                            Ajouter au panier
                                        </button>
                                    </form>
                                @endif
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                                    Rupture de stock
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Wishlist -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Votre liste de favoris est vide</h2>
                <p class="text-gray-600 mb-8">Ajoutez des produits à vos favoris pour les retrouver facilement !</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                    Découvrir nos produits
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
