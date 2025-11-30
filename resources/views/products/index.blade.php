@extends('layouts.app')

@section('title', 'Nos Produits')

@section('content')
<div class="bg-gray-50 py-4 md:py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-xs md:text-sm mb-4 md:mb-6">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">Produits</li>
            </ol>
        </nav>

        <!-- Mobile Filter Toggle Button -->
        <div class="lg:hidden mb-4">
            <button id="mobile-filter-btn" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between shadow-sm">
                <span class="font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtres
                </span>
                @if(request()->hasAny(['category', 'min_price', 'max_price', 'promotion', 'search', 'size', 'color']))
                    <span class="bg-primary-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        !
                    </span>
                @endif
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 md:gap-8">
            <!-- Sidebar Filters (Desktop: visible, Mobile: hidden by default) -->
            <aside class="lg:w-64 flex-shrink-0">
                <!-- Desktop Filters -->
                <div class="hidden lg:block bg-white rounded-lg shadow-sm p-6 sticky top-4">
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

                <!-- Mobile Filters Modal -->
                <div id="mobile-filters" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-50 hidden lg:hidden">
                    <div class="fixed inset-y-0 left-0 max-w-sm w-full bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto" id="mobile-filters-panel">
                        <div class="sticky top-0 bg-white border-b z-10 flex items-center justify-between p-4">
                            <h3 class="text-lg font-semibold">Filtres</h3>
                            <button id="mobile-filters-close" class="text-gray-700 hover:text-primary-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-4">
                            <form action="{{ route('products.index') }}" method="GET" id="mobileFilterForm">
                                <!-- Catégories -->
                                <div class="mb-6">
                                    <h4 class="font-medium mb-3 text-gray-900">Catégories</h4>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="category" value=""
                                                   {{ !request('category') ? 'checked' : '' }}
                                                   class="text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-gray-700">Toutes</span>
                                        </label>
                                        @foreach($categories as $category)
                                            <label class="flex items-center">
                                                <input type="radio" name="category" value="{{ $category->slug }}"
                                                       {{ request('category') == $category->slug ? 'checked' : '' }}
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
                                                       class="sr-only peer">
                                                <div class="w-10 h-10 rounded-full border-2 peer-checked:border-primary-600 peer-checked:ring-2 peer-checked:ring-primary-200 transition"
                                                     style="background-color: {{ $color->hex_code }}"></div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="sticky bottom-0 bg-white border-t pt-4 -mx-4 px-4 pb-4">
                                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-medium transition mb-2">
                                        Voir les produits
                                    </button>

                                    @if(request()->hasAny(['category', 'min_price', 'max_price', 'promotion', 'search', 'size', 'color']))
                                        <a href="{{ route('products.index') }}" class="block w-full text-center py-2 text-gray-600 hover:text-gray-900">
                                            Réinitialiser les filtres
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                <!-- Header -->
                <div class="bg-white rounded-lg shadow-sm p-3 md:p-4 mb-4 md:mb-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        <div>
                            <p class="text-sm md:text-base text-gray-600">
                                <span class="font-semibold text-gray-900">{{ $products->total() }}</span> produits trouvés
                            </p>
                        </div>

                        <div class="flex items-center space-x-2 md:space-x-4">
                            <label class="text-xs md:text-sm text-gray-600">Trier:</label>
                            <select name="sort"
                                    onchange="window.location.href='{{ route('products.index') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), sort: this.value}).toString()"
                                    class="text-sm border border-gray-300 rounded-lg px-2 md:px-3 py-1.5 md:py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Plus récents</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix ↑</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix ↓</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-lg transition-shadow">
                                <div class="relative">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        @if($product->main_image)
                                            <img src="{{ asset('storage/' . $product->main_image['path']) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-48 sm:h-56 md:h-64 object-cover">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-48 sm:h-56 md:h-64 object-cover">
                                        @endif
                                    </a>

                                    @if($product->is_on_sale)
                                        <div class="absolute top-2 md:top-3 left-2 md:left-3 bg-red-500 text-white px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-semibold">
                                            -{{ $product->discount_percentage }}%
                                        </div>
                                    @endif

                                    <div class="absolute top-2 md:top-3 right-2 md:right-3 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                        <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-white p-2 rounded-full shadow-md hover:bg-gray-50">
                                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="p-3 md:p-4">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <h3 class="font-semibold text-sm md:text-base text-gray-900 mb-1 md:mb-2 hover:text-primary-600 transition line-clamp-2">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    <p class="text-xs md:text-sm text-gray-600 mb-2 md:mb-3">{{ $product->sku }}</p>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($product->is_on_sale)
                                                <div class="flex items-center space-x-1 md:space-x-2">
                                                    <span class="text-base md:text-lg font-bold text-red-600">{{ number_format($product->price, 2) }} €</span>
                                                    <span class="text-xs md:text-sm text-gray-500 line-through">{{ number_format($product->compare_price, 2) }} €</span>
                                                </div>
                                            @else
                                                <span class="text-base md:text-lg font-bold text-gray-900">{{ number_format($product->price, 2) }} €</span>
                                            @endif
                                            <p class="text-xs text-gray-500">TTC</p>
                                        </div>

                                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-lg text-xs md:text-sm font-medium transition">
                                                Ajouter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 md:mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm p-8 md:p-12 text-center">
                        <svg class="w-12 h-12 md:w-16 md:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-2">Aucun produit trouvé</h3>
                        <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">Essayez de modifier vos filtres de recherche</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-4 md:px-6 py-2 rounded-lg text-sm md:text-base font-medium transition">
                            Voir tous les produits
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Mobile Filter JavaScript -->
<script>
    // Mobile Filters Toggle
    const mobileFilterBtn = document.getElementById('mobile-filter-btn');
    const mobileFilters = document.getElementById('mobile-filters');
    const mobileFiltersPanel = document.getElementById('mobile-filters-panel');
    const mobileFiltersClose = document.getElementById('mobile-filters-close');

    function openMobileFilters() {
        mobileFilters.classList.remove('hidden');
        setTimeout(() => {
            mobileFiltersPanel.classList.remove('-translate-x-full');
        }, 10);
    }

    function closeMobileFilters() {
        mobileFiltersPanel.classList.add('-translate-x-full');
        setTimeout(() => {
            mobileFilters.classList.add('hidden');
        }, 300);
    }

    mobileFilterBtn.addEventListener('click', openMobileFilters);
    mobileFiltersClose.addEventListener('click', closeMobileFilters);
    mobileFilters.addEventListener('click', (e) => {
        if (e.target === mobileFilters) {
            closeMobileFilters();
        }
    });
</script>
@endsection
