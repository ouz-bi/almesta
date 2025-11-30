@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Banner -->
<section class="bg-gradient-to-r from-navy-900 to-navy-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    Vêtements Médicaux<br>
                    <span class="text-primary-400">de Qualité</span>
                </h1>
                <p class="text-xl mb-8 text-gray-200">
                    Confort et élégance réunis. Scrubs spécialement conçus pour les professionnels de santé.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('products.index') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                        Voir les Produits
                    </a>
                    <a href="{{ route('products.index', ['promotion' => 1]) }}" class="border border-white text-white hover:bg-white hover:text-navy-900 px-8 py-3 rounded-lg font-semibold transition">
                        Promotions
                    </a>
                </div>
            </div>
            <div class="hidden lg:block flex-1">
                <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                     alt="Medical Professional" class="rounded-lg shadow-2xl">
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Nos Catégories</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-{{ $category->color ?? 'blue' }}-500 to-{{ $category->color ?? 'blue' }}-600 h-64">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-10 transition-all"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">{{ strtoupper($category->name) }}</h3>
                            <p class="text-{{ $category->color ?? 'blue' }}-100">{{ $category->description }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-8">
                    <p class="text-gray-500">Aucune catégorie disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Best Sellers -->
<section id="products" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">MEILLEURES VENTES</h2>
            <p class="text-gray-600">Nos produits les plus populaires</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($bestSellers as $product)
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

                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-white p-2 rounded-full shadow-md hover:bg-gray-50">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="p-4">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="font-semibold text-gray-900 mb-2 hover:text-primary-600 transition">
                                {{ $product->name }}
                            </h3>
                        </a>
                        <p class="text-sm text-gray-600 mb-3">{{ $product->sku }}</p>

                        <div class="flex items-center justify-between">
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

                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">Aucun produit disponible pour le moment.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                Voir Tous les Produits
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Matériaux de Qualité</h3>
                <p class="text-gray-600">100% coton et mélanges premium</p>
            </div>

            <div class="text-center">
                <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Livraison Rapide</h3>
                <p class="text-gray-600">Livraison gratuite en 1-3 jours ouvrés</p>
            </div>

            <div class="text-center">
                <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Retour Facile</h3>
                <p class="text-gray-600">Retour et échange gratuits sous 30 jours</p>
            </div>
        </div>
    </div>
</section>
@endsection
