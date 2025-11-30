@extends('layouts.app')

@section('title', 'Commande confirmée')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-3">Merci pour votre commande !</h1>
                <p class="text-lg text-gray-600 mb-6">
                    Votre commande #{{ $order->order_number }} a été confirmée avec succès.
                </p>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        Un email de confirmation a été envoyé à <strong>{{ Auth::user()->email }}</strong>
                    </p>
                </div>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-900 px-6 py-3 rounded-lg font-semibold transition">
                        Continuer mes achats
                    </a>
                    <a href="#" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Voir mes commandes
                    </a>
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Détails de la commande</h2>

                <!-- Order Info -->
                <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Numéro de commande</div>
                        <div class="font-semibold text-gray-900">{{ $order->order_number }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Date de commande</div>
                        <div class="font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Statut</div>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            En attente
                        </span>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Mode de paiement</div>
                        <div class="font-semibold text-gray-900">
                            @if($order->payment_method === 'card')
                                Carte bancaire
                            @elseif($order->payment_method === 'paypal')
                                PayPal
                            @else
                                Virement bancaire
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Adresse de livraison</h3>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium text-gray-900">{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
                            <p class="mt-1">{{ $order->shipping_address['address_line1'] }}</p>
                            @if($order->shipping_address['address_line2'])
                                <p>{{ $order->shipping_address['address_line2'] }}</p>
                            @endif
                            <p>{{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}</p>
                            <p>{{ $order->shipping_address['country'] }}</p>
                            <p class="mt-1">Tél: {{ $order->shipping_address['phone'] }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Adresse de facturation</h3>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium text-gray-900">{{ $order->billing_address['first_name'] }} {{ $order->billing_address['last_name'] }}</p>
                            <p class="mt-1">{{ $order->billing_address['address_line1'] }}</p>
                            @if($order->billing_address['address_line2'])
                                <p>{{ $order->billing_address['address_line2'] }}</p>
                            @endif
                            <p>{{ $order->billing_address['postal_code'] }} {{ $order->billing_address['city'] }}</p>
                            <p>{{ $order->billing_address['country'] }}</p>
                            <p class="mt-1">Tél: {{ $order->billing_address['phone'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Articles commandés</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 items-start">
                                @if($item->product->main_image)
                                    <img src="{{ asset('storage/' . $item->product->main_image['path']) }}"
                                         alt="{{ $item->product_name }}"
                                         class="w-20 h-20 object-cover rounded">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded"></div>
                                @endif

                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                    <div class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</div>
                                    @if($item->product_options)
                                        <div class="text-sm text-gray-600 mt-1">
                                            @if(isset($item->product_options['size']))
                                                Taille: {{ $item->product_options['size'] }}
                                            @endif
                                            @if(isset($item->product_options['color']))
                                                • Couleur: {{ $item->product_options['color'] }}
                                            @endif
                                        </div>
                                    @endif
                                    <div class="text-sm text-gray-600 mt-1">Quantité: {{ $item->quantity }}</div>
                                </div>

                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">{{ number_format($item->total_price, 2) }} €</div>
                                    <div class="text-sm text-gray-600">{{ number_format($item->unit_price, 2) }} € / unité</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Total -->
                <div class="border-t pt-6">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-gray-700">
                            <span>Sous-total</span>
                            <span>{{ number_format($order->subtotal, 2) }} €</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Livraison</span>
                            @if($order->shipping_amount > 0)
                                <span>{{ number_format($order->shipping_amount, 2) }} €</span>
                            @else
                                <span class="text-green-600">Gratuite</span>
                            @endif
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>TVA (incluse)</span>
                            <span>{{ number_format($order->tax_amount, 2) }} €</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-xl font-bold text-gray-900 pt-4 border-t">
                        <span>Total</span>
                        <span>{{ number_format($order->total_amount, 2) }} €</span>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="font-semibold text-gray-900 mb-2">Notes de commande</h3>
                        <p class="text-gray-600">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
                <h3 class="font-semibold text-gray-900 mb-3">Prochaines étapes</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Vous recevrez un email de confirmation sous peu</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Votre commande sera préparée dans les 24-48h</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Vous recevrez un email avec le numéro de suivi dès l'expédition</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Livraison estimée: 1-3 jours ouvrés</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
