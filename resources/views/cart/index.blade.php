@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">Mon Panier</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mon Panier</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex gap-6">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <a href="{{ route('products.show', $item->product->slug) }}">
                                        @if($item->product->main_image)
                                            <img src="{{ asset('storage/' . $item->product->main_image['path']) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="w-32 h-32 object-cover rounded-lg">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                                                 alt="{{ $item->product->name }}"
                                                 class="w-32 h-32 object-cover rounded-lg">
                                        @endif
                                    </a>
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <div>
                                            <a href="{{ route('products.show', $item->product->slug) }}"
                                               class="text-lg font-semibold text-gray-900 hover:text-primary-600">
                                                {{ $item->product->name }}
                                            </a>
                                            <p class="text-sm text-gray-600 mt-1">
                                                SKU: {{ $item->product_options['sku'] ?? $item->product->sku }}
                                            </p>

                                            @if($item->product_options)
                                                <div class="mt-2 space-y-1">
                                                    @if(isset($item->product_options['size']))
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Taille:</span> {{ $item->product_options['size'] }}
                                                        </p>
                                                    @endif
                                                    @if(isset($item->product_options['color']))
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Couleur:</span> {{ $item->product_options['color'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Remove Button -->
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Price and Quantity -->
                                    <div class="flex items-center justify-between mt-4">
                                        <!-- Quantity Selector -->
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-3">
                                            @csrf
                                            @method('PATCH')

                                            <button type="button"
                                                    onclick="updateQuantity({{ $item->id }}, -1)"
                                                    class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>

                                            <input type="number"
                                                   name="quantity"
                                                   id="quantity-{{ $item->id }}"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   class="w-16 text-center border border-gray-300 rounded-lg py-1 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                                   onchange="this.form.submit()">

                                            <button type="button"
                                                    onclick="updateQuantity({{ $item->id }}, 1)"
                                                    class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Item Total -->
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900">{{ number_format($item->total, 2) }} €</p>
                                            <p class="text-sm text-gray-500">{{ number_format($item->price, 2) }} € / unité</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Clear Cart -->
                    <div class="flex justify-between items-center pt-4">
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                Vider le panier
                            </button>
                        </form>

                        <a href="{{ route('products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                            Continuer mes achats
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Résumé de la commande</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-700">
                                <span>Sous-total ({{ $cartItems->sum('quantity') }} articles)</span>
                                <span class="font-semibold">{{ number_format($subtotal, 2) }} €</span>
                            </div>

                            <div class="flex justify-between text-gray-700">
                                <span>Livraison</span>
                                @if($shipping > 0)
                                    <span class="font-semibold">{{ number_format($shipping, 2) }} €</span>
                                @else
                                    <span class="text-green-600 font-semibold">Gratuite</span>
                                @endif
                            </div>

                            @if($subtotal < 50 && $subtotal > 0)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm text-blue-800">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Plus que {{ number_format(50 - $subtotal, 2) }} € pour la livraison gratuite !
                                    </p>
                                </div>
                            @endif

                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>{{ number_format($total, 2) }} €</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">TVA incluse</p>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('checkout.index') }}" class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center px-6 py-3 rounded-lg font-semibold transition mb-3">
                                Passer la commande
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center px-6 py-3 rounded-lg font-semibold transition mb-3">
                                Se connecter pour commander
                            </a>
                            <p class="text-sm text-gray-600 text-center">
                                Nouveau client ?
                                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                    Créer un compte
                                </a>
                            </p>
                        @endauth

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Paiement 100% sécurisé
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                                </svg>
                                Livraison rapide 1-3 jours
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                                Retours gratuits sous 30 jours
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5H20"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Votre panier est vide</h2>
                <p class="text-gray-600 mb-8">Découvrez nos produits et ajoutez-les à votre panier !</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                    Découvrir nos produits
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function updateQuantity(itemId, change) {
    const input = document.getElementById(`quantity-${itemId}`);
    const currentValue = parseInt(input.value);
    const newValue = currentValue + change;

    if (newValue >= 1) {
        input.value = newValue;
        input.form.submit();
    }
}
</script>
@endpush
@endsection
