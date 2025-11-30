@extends('layouts.app')

@section('title', 'Finaliser ma commande')

@section('content')
<div class="bg-gray-50 py-4 md:py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-xs md:text-sm mb-4 md:mb-6">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-primary-600">Panier</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">Commande</li>
            </ol>
        </nav>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-8">Finaliser ma commande</h1>

        {{-- Messages Flash --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 md:mb-6 flex items-start text-sm md:text-base">
                <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 md:mb-6 flex items-start text-sm md:text-base">
                <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Erreurs de validation --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 md:mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold mb-2 text-sm md:text-base">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="list-disc list-inside space-y-1 text-xs md:text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
                <!-- Formulaire -->
                <div class="lg:col-span-2 space-y-4 md:space-y-6">
                    <!-- Adresse de livraison -->
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 md:mb-6 gap-3">
                            <h2 class="text-lg md:text-xl font-bold text-gray-900 flex items-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-full bg-primary-600 text-white mr-2 md:mr-3 text-sm md:text-base flex-shrink-0">1</span>
                                Adresse de livraison
                            </h2>
                            <button type="button" onclick="openModal('addShippingModal')" class="text-primary-600 hover:text-primary-700 text-sm font-medium whitespace-nowrap">
                                + Nouvelle adresse
                            </button>
                        </div>

                        @error('shipping_address_id')
                            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($shippingAddresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($shippingAddresses as $address)
                                    <label class="flex items-start p-3 md:p-4 border-2 rounded-lg cursor-pointer hover:border-primary-600 transition
                                        {{ old('shipping_address_id') == $address->id || (!old('shipping_address_id') && $address->is_default) ? 'border-primary-600 bg-primary-50' : 'border-gray-200' }}">
                                        <input type="radio" name="shipping_address_id" value="{{ $address->id }}"
                                               {{ old('shipping_address_id') == $address->id || (!old('shipping_address_id') && $address->is_default) ? 'checked' : '' }}
                                               required class="mt-1 text-primary-600 focus:ring-primary-500 flex-shrink-0">
                                        <div class="ml-3 flex-1 min-w-0">
                                            <div class="font-semibold text-sm md:text-base text-gray-900">{{ $address->full_name }}</div>
                                            <div class="text-xs md:text-sm text-gray-600 mt-1">
                                                {{ $address->address_line1 }}@if($address->address_line2), {{ $address->address_line2 }}@endif<br>
                                                {{ $address->postal_code }} {{ $address->city }}, {{ $address->country }}<br>
                                                Tél: {{ $address->phone }}
                                            </div>
                                            @if($address->is_default)
                                                <span class="inline-block mt-2 text-xs bg-primary-100 text-primary-800 px-2 py-1 rounded">Par défaut</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 md:py-8">
                                <svg class="w-12 h-12 md:w-16 md:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-sm md:text-base text-gray-600 mb-4">Aucune adresse enregistrée</p>
                                <button type="button" onclick="openModal('addShippingModal')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 md:px-6 py-2 rounded-lg text-sm md:text-base font-medium transition">
                                    Ajouter une adresse
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Adresse de facturation -->
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 md:mb-6 gap-3">
                            <h2 class="text-lg md:text-xl font-bold text-gray-900 flex items-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-full bg-primary-600 text-white mr-2 md:mr-3 text-sm md:text-base flex-shrink-0">2</span>
                                Adresse de facturation
                            </h2>
                            <button type="button" onclick="openModal('addBillingModal')" class="text-primary-600 hover:text-primary-700 text-sm font-medium whitespace-nowrap">
                                + Nouvelle adresse
                            </button>
                        </div>

                        @error('billing_address_id')
                            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                                {{ $message }}
                            </div>
                        @enderror

                        <label class="flex items-center mb-4">
                            <input type="checkbox" name="same_as_shipping" id="sameAsShipping" {{ old('same_as_shipping') ? 'checked' : '' }}
                                   onchange="toggleBillingAddress()" class="text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm md:text-base text-gray-700">Identique à l'adresse de livraison</span>
                        </label>

                        <div id="billingAddressSection" style="{{ old('same_as_shipping') ? 'display: none;' : '' }}">
                            @if($billingAddresses->count() > 0)
                                <div class="space-y-3">
                                    @foreach($billingAddresses as $address)
                                        <label class="flex items-start p-3 md:p-4 border-2 rounded-lg cursor-pointer hover:border-primary-600 transition
                                            {{ old('billing_address_id') == $address->id || (!old('billing_address_id') && $address->is_default) ? 'border-primary-600 bg-primary-50' : 'border-gray-200' }}">
                                            <input type="radio" name="billing_address_id" value="{{ $address->id }}"
                                                   {{ old('billing_address_id') == $address->id || (!old('billing_address_id') && $address->is_default) ? 'checked' : '' }}
                                                   class="mt-1 text-primary-600 focus:ring-primary-500 billing-address-radio flex-shrink-0">
                                            <div class="ml-3 flex-1 min-w-0">
                                                <div class="font-semibold text-sm md:text-base text-gray-900">{{ $address->full_name }}</div>
                                                <div class="text-xs md:text-sm text-gray-600 mt-1">
                                                    {{ $address->address_line1 }}@if($address->address_line2), {{ $address->address_line2 }}@endif<br>
                                                    {{ $address->postal_code }} {{ $address->city }}, {{ $address->country }}<br>
                                                    Tél: {{ $address->phone }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 md:py-8">
                                    <p class="text-sm md:text-base text-gray-600 mb-4">Aucune adresse de facturation</p>
                                    <button type="button" onclick="openModal('addBillingModal')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 md:px-6 py-2 rounded-lg text-sm md:text-base font-medium transition">
                                        Ajouter une adresse
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mode de paiement -->
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                        <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6 flex items-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-full bg-primary-600 text-white mr-2 md:mr-3 text-sm md:text-base flex-shrink-0">3</span>
                            Mode de paiement
                        </h2>

                        @error('payment_method')
                            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="space-y-3">
                            @foreach([
                                ['value' => 'card', 'label' => 'Carte bancaire', 'desc' => 'Visa, Mastercard, American Express'],
                                ['value' => 'paypal', 'label' => 'PayPal', 'desc' => 'Paiement sécurisé via PayPal'],
                                ['value' => 'bank_transfer', 'label' => 'Virement bancaire', 'desc' => 'Instructions par email']
                            ] as $method)
                                <label class="flex items-start p-3 md:p-4 border-2 rounded-lg cursor-pointer hover:border-primary-600 transition
                                    {{ old('payment_method', 'card') == $method['value'] ? 'border-primary-600 bg-primary-50' : 'border-gray-200' }}">
                                    <input type="radio" name="payment_method" value="{{ $method['value'] }}"
                                           {{ old('payment_method', 'card') == $method['value'] ? 'checked' : '' }}
                                           required class="mt-1 text-primary-600 focus:ring-primary-500 flex-shrink-0">
                                    <div class="ml-3">
                                        <div class="font-semibold text-sm md:text-base text-gray-900">{{ $method['label'] }}</div>
                                        <div class="text-xs md:text-sm text-gray-600">{{ $method['desc'] }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                        <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-3 md:mb-4">Notes (optionnel)</h2>
                        <textarea name="notes" rows="3" placeholder="Informations supplémentaires..."
                                  class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Récapitulatif -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 lg:sticky lg:top-4">
                        <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-3 md:mb-4">Récapitulatif</h2>

                        <div class="space-y-3 mb-4 md:mb-6 max-h-48 md:max-h-64 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex gap-2 md:gap-3">
                                    <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image['path']) : 'https://via.placeholder.com/100' }}"
                                         alt="{{ $item->product->name }}" class="w-12 h-12 md:w-16 md:h-16 object-cover rounded flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs md:text-sm font-medium text-gray-900 line-clamp-2">{{ $item->product->name }}</div>
                                        @if($item->product_options)
                                            <div class="text-xs text-gray-600">
                                                @if(isset($item->product_options['size'])){{ $item->product_options['size'] }}@endif
                                                @if(isset($item->product_options['color'])) • {{ $item->product_options['color'] }}@endif
                                            </div>
                                        @endif
                                        <div class="text-xs md:text-sm text-gray-600">Qté: {{ $item->quantity }}</div>
                                        <div class="text-sm md:text-base font-semibold text-gray-900">{{ number_format($item->total, 2) }} €</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-3 md:pt-4 space-y-2 md:space-y-3">
                            <div class="flex justify-between text-sm md:text-base text-gray-700">
                                <span>Sous-total</span>
                                <span class="font-semibold">{{ number_format($subtotal, 2) }} €</span>
                            </div>
                            <div class="flex justify-between text-sm md:text-base text-gray-700">
                                <span>Livraison</span>
                                <span class="font-semibold {{ $shipping > 0 ? '' : 'text-green-600' }}">
                                    {{ $shipping > 0 ? number_format($shipping, 2) . ' €' : 'Gratuite' }}
                                </span>
                            </div>
                            <div class="flex justify-between text-xs md:text-sm text-gray-600">
                                <span>TVA (incluse)</span>
                                <span>{{ number_format($tax, 2) }} €</span>
                            </div>
                            <div class="border-t pt-2 md:pt-3">
                                <div class="flex justify-between text-base md:text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>{{ number_format($total, 2) }} €</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 md:mt-6 bg-primary-600 hover:bg-primary-700 text-white px-4 md:px-6 py-2.5 md:py-3 rounded-lg text-sm md:text-base font-semibold transition">
                            Confirmer la commande
                        </button>

                        <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t space-y-2 text-xs md:text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Paiement sécurisé
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
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

{{-- Modal Adresse de livraison --}}
@include('checkout.partials.address-modal', ['modalId' => 'addShippingModal', 'title' => 'Nouvelle adresse de livraison', 'type' => 'shipping'])

{{-- Modal Adresse de facturation --}}
@include('checkout.partials.address-modal', ['modalId' => 'addBillingModal', 'title' => 'Nouvelle adresse de facturation', 'type' => 'billing'])

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function toggleBillingAddress() {
    const checkbox = document.getElementById('sameAsShipping');
    const section = document.getElementById('billingAddressSection');
    const radios = document.querySelectorAll('.billing-address-radio');
    const shippingRadio = document.querySelector('input[name="shipping_address_id"]:checked');

    if (checkbox.checked) {
        section.style.display = 'none';
        radios.forEach(r => r.required = false);

        let hidden = document.getElementById('hidden_billing_address');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.id = 'hidden_billing_address';
            hidden.name = 'billing_address_id';
            section.appendChild(hidden);
        }
        hidden.value = shippingRadio ? shippingRadio.value : '';
    } else {
        section.style.display = 'block';
        radios.forEach(r => r.required = true);
        const hidden = document.getElementById('hidden_billing_address');
        if (hidden) hidden.remove();
    }
}

// Fermer modal au clic extérieur
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal(modal.id);
        }
    });
});
</script>
@endpush
@endsection
