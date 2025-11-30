<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Almesta Clone') }} - @yield('title', 'Vêtements Médicaux de Qualité')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <!-- Top Bar -->
        <div class="bg-navy-900 text-white text-sm">
            <div class="container mx-auto px-4 py-2">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        <span class="text-gray-100">Notre nouvelle couleur bleu marine arrive !</span>
                    </div>
                    <div class="flex space-x-4 text-gray-100">
                        <a href="#" class="hover:text-white transition">Contact</a>
                        @auth
                            <span class="text-gray-100">Bonjour, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="hover:text-white transition">Déconnexion</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-white transition">Connexion</a>
                            <a href="{{ route('register') }}" class="hover:text-white transition">Inscription</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-navy-900">
                        ALMESTA
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-lg mx-8">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   placeholder="Rechercher un produit..."
                                   value="{{ request('search') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="flex items-center text-gray-700 hover:text-primary-600 transition">
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Favoris
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center text-gray-700 hover:text-primary-600 transition">
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Favoris
                        </a>
                    @endauth

                    @auth
                        <div class="flex items-center text-gray-700">
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            <span class="font-semibold">{{ Auth::user()->points }}</span> Points
                        </div>

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-700 hover:text-primary-600 transition">
                                <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Admin
                            </a>
                        @endif
                    @endauth

                    <a href="{{ route('cart.index') }}" class="flex items-center text-gray-700 hover:text-primary-600 transition relative">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5H20"></path>
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                        Panier
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="bg-gray-100 border-t">
            <div class="container mx-auto px-4">
                <div class="flex space-x-8">
                    @php
                        $categories = \App\Models\Category::active()
                            ->mainCategories()
                            ->orderBy('sort_order')
                            ->get();
                    @endphp

                    @foreach($categories as $category)
                        <div class="relative group">
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                               class="flex items-center px-4 py-3 text-gray-700 hover:text-primary-600 transition font-medium">
                                {{ strtoupper($category->name) }}
                                @if($category->children->count() > 0)
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </a>

                            @if($category->children->count() > 0)
                                <!-- Dropdown Menu -->
                                <div class="absolute left-0 mt-0 w-64 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <div class="py-2">
                                        @foreach($category->children as $subCategory)
                                            <a href="{{ route('products.index', ['category' => $subCategory->slug]) }}"
                                               class="block px-6 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition">
                                                {{ $subCategory->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <a href="{{ route('products.index', ['promotion' => 1]) }}"
                       class="px-4 py-3 text-gray-700 hover:text-primary-600 transition font-medium">
                        PROMOTIONS
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    <footer class="bg-navy-900 text-white mt-12">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">ALMESTA</h3>
                    <p class="text-gray-300">
                        Nous nous démarquons par notre large gamme de produits dans le domaine de la santé et du médical.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Catégories</h4>
                    <ul class="space-y-2 text-gray-300">
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="hover:text-white transition">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Service Client</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition">Livraison</a></li>
                        <li><a href="#" class="hover:text-white transition">Retour & Échange</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Mon Compte</h4>
                    <ul class="space-y-2 text-gray-300">
                        @auth
                            <li><a href="{{ route('wishlist.index') }}" class="hover:text-white transition">Mes Favoris</a></li>
                            <li><a href="#" class="hover:text-white transition">Mes Commandes</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="hover:text-white transition">Déconnexion</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition">Se Connecter</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition">S'inscrire</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; 2024 Almesta. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
