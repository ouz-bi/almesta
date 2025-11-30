@extends('layouts.app')

@section('title', 'Nos Produits')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">Produits</li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <h3 class="text-lg font-semibold mb-4">Filtres</h3>

                    <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                        <!-- Catégories -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3 text-gray-900">Catégories</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="category" value=""
                                           {{ !request('category') ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-gray-700">Toutes</span>
                                </label>
                                @foreach($categories as $category)
                                    <label class="flex items-center">
                                        <input type="radio" name="category" value="{{ $category->slug }}"
                                               {{ request('category') == $category->slug ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-gray-700">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="mb-6 border-t pt-6">
                            <h4 class="font-medium mb-3 text-gray-900">Prix</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm text-gray-600">Min (€)</label>
                                    <input type="number" name="min_price"
                                           value="{{ request('min_price') }}"
                                           placeholder="0"
                                           class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600">Max (€)</label>
                                    <input type="number" name="max_price"
                                           value="{{ request('max_price') }}"
                                           placeholder="200"
                                           class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>

                        <!-- Promotions -->
                        <div class="mb-6 border-t pt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="promotion" value="1"
                                       {{ request('promotion') ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-gray-700">En promotion</span>
                            </label>
                        </div>

                        <!-- Tailles -->
                        @if($sizes->count() > 0)
                        <div class="mb-6 border-t pt-6">
                            <h4 class="font-medium mb-3 text-gray-900">Tailles</h4>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($sizes as $size)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="size" value="{{ $size->id }}"
                                               {{ request('size') == $size->id ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-gray-700">{{ $size->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Couleurs -->
                        @if($colors->count() > 0)
                        <div class="mb-6 border-t pt-6">
                            <h4 class="font-medium mb-3 text-gray-900">Couleurs</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                    <label class="cursor-pointer" title="{{ $color->name }}">
                                        <input type="checkbox" name="color" value="{{ $color->id }}"
                                               {{ request('color') == $color->id ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="sr-only peer">
                                        <div class="w-8 h-8 rounded-full border-2 peer-checked:border-primary-600 peer-checked:ring-2 peer-checked:ring-primary-200 transition"
                                             style="background-color: {{ $color->hex_code }}"></div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg font-medium transition">
                            Appliquer les filtres
                        </button>

                        @if(request()->hasAny(['category', 'min_price', 'max_price', 'promotion', 'search']))
                            <a href="{{ route('products.index') }}" class="block w-full text-center mt-2 text-gray-600 hover:text-gray-900">
                                Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                <!-- Header -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex justify-between items-center">
                    <div>
                        <p class="text-gray-600">
                            <span class="font-semibold text-gray-900">{{ $products->total() }}</span> produits trouvés
                        </p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <label class="text-sm text-gray-600">Trier par:</label>
                        <select name="sort"
                                onchange="window.location.href='{{ route('products.index') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), sort: this.value}).toString()"
                                class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Plus récents</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                        </select>
                    </div>
                </div>

                <!-- Products -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
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
                                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                                Ajouter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun produit trouvé</h3>
                        <p class="text-gray-600 mb-6">Essayez de modifier vos filtres de recherche</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Voir tous les produits
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
