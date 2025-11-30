@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-primary-600">Produits</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-gray-600 hover:text-primary-600">{{ $product->category->name }}</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                <!-- Image Gallery -->
                <div>
                    <div class="mb-4">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image['path']) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full rounded-lg"
                                 id="mainImage">
                        @else
                            <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                 alt="{{ $product->name }}"
                                 class="w-full rounded-lg"
                                 id="mainImage">
                        @endif
                    </div>

                    @if($product->images && count($product->images) > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $index => $image)
                                <img src="{{ asset('storage/' . $image['path']) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-24 object-cover rounded-lg cursor-pointer border-2 {{ $index === 0 ? 'border-primary-600' : 'border-transparent hover:border-primary-600' }}"
                                     onclick="changeMainImage(this.src, this)">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div>
                    <div class="mb-4">
                        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                           class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            {{ $product->category->name }}
                        </a>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <p class="text-gray-600 mb-4">SKU: <span id="currentSku">{{ $product->sku }}</span></p>

                    <!-- Price -->
                    <div class="mb-6">
                        @if($product->is_on_sale)
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl font-bold text-red-600" id="currentPrice">{{ number_format($product->price, 2) }} €</span>
                                <span class="text-xl text-gray-500 line-through" id="comparePrice">{{ number_format($product->compare_price, 2) }} €</span>
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            </div>
                        @else
                            <span class="text-3xl font-bold text-gray-900" id="currentPrice">{{ number_format($product->price, 2) }} €</span>
                        @endif
                        <p class="text-sm text-gray-500 mt-1">TTC, livraison non incluse</p>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6" id="stockStatus">
                        @php
                            $totalStock = $product->total_stock;
                        @endphp
                        @if($totalStock > 10)
                            <span class="inline-flex items-center text-green-600">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                En stock
                            </span>
                        @elseif($totalStock > 0)
                            <span class="inline-flex items-center text-orange-600">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Stock limité
                            </span>
                        @else
                            <span class="inline-flex items-center text-red-600">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Rupture de stock
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-6 border-t border-b py-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
                        @if($product->short_description)
                            <p class="text-gray-700 leading-relaxed mb-3">{{ $product->short_description }}</p>
                        @endif
                        @if($product->description)
                            <p class="text-gray-600 leading-relaxed text-sm">{{ $product->description }}</p>
                        @endif
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="variant_id" id="selectedVariantId">

                        @if($product->has_variants && $product->productVariants->count() > 0)
                            <!-- Size Selection -->
                            @if($product->available_sizes->count() > 0)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Taille <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($product->available_sizes as $size)
                                            <label class="cursor-pointer">
                                                <input type="radio"
                                                       name="size_id"
                                                       value="{{ $size->id }}"
                                                       class="sr-only peer variant-option"
                                                       data-type="size"
                                                       required
                                                       onchange="updateVariant()">
                                                <div class="px-4 py-2 border border-gray-300 rounded-lg peer-checked:border-primary-600 peer-checked:bg-primary-50 peer-checked:text-primary-600 hover:border-primary-400 transition">
                                                    {{ $size->name }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Color Selection -->
                            @if($product->available_colors->count() > 0)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Couleur <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($product->available_colors as $color)
                                            <label class="cursor-pointer group" title="{{ $color->name }}">
                                                <input type="radio"
                                                       name="color_id"
                                                       value="{{ $color->id }}"
                                                       class="sr-only peer variant-option"
                                                       data-type="color"
                                                       required
                                                       onchange="updateVariant()">
                                                <div class="relative">
                                                    <div class="w-10 h-10 rounded-full border-2 peer-checked:border-primary-600 peer-checked:ring-2 peer-checked:ring-primary-200 transition shadow-sm"
                                                         style="background-color: {{ $color->hex_code }}"></div>
                                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 peer-checked:opacity-100">
                                                        <svg class="w-5 h-5 text-white drop-shadow" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <p class="text-xs text-center mt-1 text-gray-600">{{ $color->name }}</p>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Quantity -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantité</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="decrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->total_stock }}"
                                       class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <button type="button" onclick="incrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex space-x-4">
                            <button type="submit"
                                    class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                                    id="addToCartBtn"
                                    {{ $product->total_stock <= 0 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5H20"></path>
                                </svg>
                                Ajouter au panier
                            </button>
                        </div>
                    </form>

                    <!-- Wishlist Button -->
                    @auth
                    <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Ajouter aux favoris
                        </button>
                    </form>
                    @endauth

                    <!-- Additional Info -->
                    <div class="mt-8 space-y-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Livraison gratuite à partir de 50€
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Retour gratuit sous 30 jours
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Paiement sécurisé
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <section class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits similaires</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-lg transition-shadow">
                            <div class="relative">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                    @if($relatedProduct->main_image)
                                        <img src="{{ asset('storage/' . $relatedProduct->main_image['path']) }}"
                                             alt="{{ $relatedProduct->name }}"
                                             class="w-full h-64 object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                                             alt="{{ $relatedProduct->name }}"
                                             class="w-full h-64 object-cover">
                                    @endif
                                </a>
                                @if($relatedProduct->is_on_sale)
                                    <div class="absolute top-3 left-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        -{{ $relatedProduct->discount_percentage }}%
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                    <h3 class="font-semibold text-gray-900 mb-2 hover:text-primary-600 transition">
                                        {{ $relatedProduct->name }}
                                    </h3>
                                </a>
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($relatedProduct->is_on_sale)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-lg font-bold text-red-600">{{ number_format($relatedProduct->price, 2) }} €</span>
                                                <span class="text-sm text-gray-500 line-through">{{ number_format($relatedProduct->compare_price, 2) }} €</span>
                                            </div>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">{{ number_format($relatedProduct->price, 2) }} €</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Données des variantes pour JS
const productVariants = @json($product->productVariants);
const hasVariants = {{ $product->has_variants ? 'true' : 'false' }};

function changeMainImage(src, element) {
    document.getElementById('mainImage').src = src;

    document.querySelectorAll('img[onclick]').forEach(img => {
        img.classList.remove('border-primary-600');
        img.classList.add('border-transparent');
    });
    element.classList.remove('border-transparent');
    element.classList.add('border-primary-600');
}

function updateVariant() {
    if (!hasVariants) return;

    const sizeId = document.querySelector('input[name="size_id"]:checked')?.value;
    const colorId = document.querySelector('input[name="color_id"]:checked')?.value;

    // Chercher la variante correspondante
    const variant = productVariants.find(v => {
        return (!sizeId || v.size_id == sizeId) && (!colorId || v.color_id == colorId);
    });

    if (variant) {
        document.getElementById('selectedVariantId').value = variant.id;
        document.getElementById('currentSku').textContent = variant.sku;

        // Mettre à jour le prix
        const price = variant.price || {{ $product->price }};
        document.getElementById('currentPrice').textContent = price.toFixed(2) + ' €';

        // Mettre à jour le stock
        const stockStatus = document.getElementById('stockStatus');
        const quantity = document.getElementById('quantity');
        const addToCartBtn = document.getElementById('addToCartBtn');

        quantity.max = variant.stock_quantity;

        if (variant.stock_quantity > 10) {
            stockStatus.innerHTML = `
                <span class="inline-flex items-center text-green-600">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    En stock
                </span>`;
            addToCartBtn.disabled = false;
        } else if (variant.stock_quantity > 0) {
            stockStatus.innerHTML = `
                <span class="inline-flex items-center text-orange-600">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Stock limité (${variant.stock_quantity} restants)
                </span>`;
            addToCartBtn.disabled = false;
        } else {
            stockStatus.innerHTML = `
                <span class="inline-flex items-center text-red-600">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    Rupture de stock
                </span>`;
            addToCartBtn.disabled = true;
        }
    }
}

function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const min = parseInt(input.min);
    const current = parseInt(input.value);
    if (current > min) {
        input.value = current - 1;
    }
}
</script>
@endpush
@endsection
