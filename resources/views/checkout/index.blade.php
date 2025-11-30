@extends('layouts.app')

@section('title', 'Finaliser ma commande')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-primary-600">Panier</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">Commande</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finaliser ma commande</h1>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Checkout Steps -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Step 1: Shipping Address -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-600 text-white mr-3">1</span>
                                Adresse de livraison
                            </h2>
                            <button type="button"
                                    onclick="document.getElementById('addShippingModal').classList.remove('hidden')"
                                    class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                + Nouvelle adresse
                            </button>
                        </div>

                        @if($shippingAddresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($shippingAddresses as $address)
                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-primary-600 transition {{ $address->is_default ? 'border-primary-600 bg-primary-50' : 'border-gray-200' }}">
                                        <input type="radio"
                                               name="shipping_address_id"
                                               value="{{ $address->id }}"
                                               {{ $address->is_default ? 'checked' : '' }}
                                               required
                                               class="mt-1 text-primary-600 focus:ring-primary-500">
                                        <div class="ml-3 flex-1">
                                            <div class="font-semibold text-gray-900">{{ $address->full_name }}</div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                {{ $address->address_line1 }}
                                                @if($address->address_line2), {{ $address->address_line2 }}@endif
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $address->postal_code }} {{ $address->city }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $address->country }}
                                            </div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                Tél: {{ $address->phone }}
                                            </div>
                                            @if($address->is_default)
                                                <span class="inline-block mt-2 text-xs bg-primary-100 text-primary-800 px-2 py-1 rounded">
                                                    Adresse par défaut
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-gray-600 mb-4">Aucune adresse enregistrée</p>
                                <button type="button"
                                        onclick="document.getElementById('addShippingModal').classList.remove('hidden')"
                                        class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                    Ajouter une adresse
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Step 2: Billing Address -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-600 text-white mr-3">2</span>
                                Adresse de facturation
                            </h2>
                            <button type="button"
                                    onclick="document.getElementById('addBillingModal').classList.remove('hidden')"
                                    class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                + Nouvelle adresse
                            </button>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="same_as_shipping"
                                       id="sameAsShipping"
                                       onchange="toggleBillingAddress()"
                                       class="text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-gray-700">Identique à l'adresse de livraison</span>
                            </label>
                        </div>

                        <div id="billingAddressSection">
                            @if($billingAddresses->count() > 0)
                                <div class="space-y-3">
                                    @foreach($billingAddresses as $address)
                                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-primary-600 transition {{ $address->is_default ? 'border-primary-600 bg-primary-50' : 'border-gray-200' }}">
                                            <input type="radio"
                                                   name="billing_address_id"
                                                   value="{{ $address->id }}"
                                                   {{ $address->is_default ? 'checked' : '' }}
                                                   class="mt-1 text-primary-600 focus:ring-primary-500 billing-address-radio">
                                            <div class="ml-3 flex-1">
                                                <div class="font-semibold text-gray-900">{{ $address->full_name }}</div>
                                                <div class="text-sm text-gray-600 mt-1">
                                                    {{ $address->address_line1 }}
                                                    @if($address->address_line2), {{ $address->address_line2 }}@endif
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $address->postal_code }} {{ $address->city }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $address->country }}
                                                </div>
                                                <div class="text-sm text-gray-600 mt-1">
                                                    Tél: {{ $address->phone }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-600 mb-4">Aucune adresse de facturation enregistrée</p>
                                    <button type="button"
                                            onclick="document.getElementById('addBillingModal').classList.remove('hidden')"
                                            class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                        Ajouter une adresse
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Step 3: Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-600 text-white mr-3">3</span>
                            Mode de paiement
                        </h2>

                        <div class="space-y-3">
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-primary-600 transition border-primary-600 bg-primary-50">
                                <input type="radio"
                                       name="payment_method"
                                       value="card"
                                       checked
                                       required
                                       class="text-primary-600 focus:ring-primary-500">
                                <div class="ml-3">
                                    <div class="font-semibold text-gray-900">Carte bancaire</div>
                                    <div class="text-sm text-gray-600">Visa, Mastercard, American Express</div>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-600 transition">
                                <input type="radio"
                                       name="payment_method"
                                       value="paypal"
                                       class="text-primary-600 focus:ring-primary-500">
                                <div class="ml-3">
                                    <div class="font-semibold text-gray-900">PayPal</div>
                                    <div class="text-sm text-gray-600">Paiement sécurisé via PayPal</div>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary-600 transition">
                                <input type="radio"
                                       name="payment_method"
                                       value="bank_transfer"
                                       class="text-primary-600 focus:ring-primary-500">
                                <div class="ml-3">
                                    <div class="font-semibold text-gray-900">Virement bancaire</div>
                                    <div class="text-sm text-gray-600">Vous recevrez les instructions par email</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Notes de commande (optionnel)</h2>
                        <textarea name="notes"
                                  rows="4"
                                  placeholder="Informations supplémentaires concernant votre commande..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Récapitulatif</h2>

                        <!-- Cart Items -->
                        <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex gap-3">
                                    @if($item->product->main_image)
                                        <img src="{{ asset('storage/' . $item->product->main_image['path']) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded"></div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        @if($item->product_options)
                                            <div class="text-xs text-gray-600">
                                                @if(isset($item->product_options['size']))
                                                    Taille: {{ $item->product_options['size'] }}
                                                @endif
                                                @if(isset($item->product_options['color']))
                                                    • Couleur: {{ $item->product_options['color'] }}
                                                @endif
                                            </div>
                                        @endif
                                        <div class="text-sm text-gray-600">Qté: {{ $item->quantity }}</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ number_format($item->total, 2) }} €</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pricing -->
                        <div class="border-t pt-4 space-y-3">
                            <div class="flex justify-between text-gray-700">
                                <span>Sous-total</span>
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

                            <div class="flex justify-between text-sm text-gray-600">
                                <span>TVA (incluse)</span>
                                <span>{{ number_format($tax, 2) }} €</span>
                            </div>

                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>{{ number_format($total, 2) }} €</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full mt-6 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                            Confirmer la commande
                        </button>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Paiement sécurisé
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Données protégées
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Shipping Address -->
<div id="addShippingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Nouvelle adresse de livraison</h3>
                <button type="button" onclick="document.getElementById('addShippingModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('checkout.address.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="shipping">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                        <input type="text" name="first_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse ligne 1 *</label>
                        <input type="text" name="address_line1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse ligne 2</label>
                        <input type="text" name="address_line2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Code postal *</label>
                        <input type="text" name="postal_code" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                        <input type="text" name="city" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                        <select name="country" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="France">France</option>
                            <option value="Belgique">Belgique</option>
                            <option value="Suisse">Suisse</option>
                            <option value="Luxembourg">Luxembourg</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_default" value="1" class="text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Définir comme adresse par défaut</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="document.getElementById('addShippingModal').classList.add('hidden')" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Billing Address (même structure que shipping) -->
<div id="addBillingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Nouvelle adresse de facturation</h3>
                <button type="button" onclick="document.getElementById('addBillingModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('checkout.address.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="billing">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                        <input type="text" name="first_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse ligne 1 *</label>
                        <input type="text" name="address_line1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse ligne 2</label>
                        <input type="text" name="address_line2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Code postal *</label>
                        <input type="text" name="postal_code" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                        <input type="text" name="city" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                        <select name="country" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="France">France</option>
                            <option value="Belgique">Belgique</option>
                            <option value="Suisse">Suisse</option>
                            <option value="Luxembourg">Luxembourg</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_default" value="1" class="text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Définir comme adresse par défaut</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="document.getElementById('addBillingModal').classList.add('hidden')" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleBillingAddress() {
    const checkbox = document.getElementById('sameAsShipping');
    const billingSection = document.getElementById('billingAddressSection');
    const billingRadios = document.querySelectorAll('.billing-address-radio');
    const shippingRadio = document.querySelector('input[name="shipping_address_id"]:checked');

    if (checkbox.checked) {
        billingSection.style.display = 'none';
        billingRadios.forEach(radio => radio.required = false);

        // Créer un input caché avec l'adresse de livraison sélectionnée
        let hiddenInput = document.getElementById('hidden_billing_address');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'hidden_billing_address';
            hiddenInput.name = 'billing_address_id';
            billingSection.appendChild(hiddenInput);
        }
        hiddenInput.value = shippingRadio ? shippingRadio.value : '';
    } else {
        billingSection.style.display = 'block';
        billingRadios.forEach(radio => radio.required = true);
        const hiddenInput = document.getElementById('hidden_billing_address');
        if (hiddenInput) {
            hiddenInput.remove();
        }
    }
}
</script>
@endpush
@endsection
