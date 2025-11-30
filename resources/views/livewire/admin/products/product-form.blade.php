<div>
    <form wire:submit="save">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    {{ $isEditing ? 'Modifier le produit' : 'Nouveau produit' }}
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    {{ $isEditing ? 'Modifiez les informations du produit' : 'Créez un nouveau produit pour votre boutique' }}
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-6 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Informations générales</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Name -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nom du produit *
                            </label>
                            <div class="mt-1">
                                <input wire:model.blur="name" 
                                       type="text" 
                                       id="name" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- SKU -->
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700">
                                    SKU *
                                </label>
                                <div class="mt-1">
                                    <input wire:model.blur="sku" 
                                           type="text" 
                                           id="sku" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('sku') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                </div>
                                @error('sku')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">Code unique obligatoire du produit.</p>
                            </div>

                            <!-- Reference -->
                            <div>
                                <label for="reference" class="block text-sm font-medium text-gray-700">
                                    Référence
                                </label>
                                <div class="mt-1">
                                    <input wire:model.blur="reference" 
                                           type="text" 
                                           id="reference" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('reference') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                </div>
                                @error('reference')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">Optionnel. Code unique du produit.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-1 gap-6">
                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">
                                    Catégorie *
                                </label>
                                <div class="mt-1">
                                    <select wire:model.blur="category_id" 
                                            id="category_id"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                        <option value="">Sélectionnez une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description *
                            </label>
                            <div class="mt-1">
                                <textarea wire:model.blur="description" 
                                          id="description" 
                                          rows="5" 
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                          placeholder="Description détaillée du produit..."></textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">Maximum 2000 caractères.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">
                                    Prix (€) *
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model.blur="price" 
                                           type="number" 
                                           step="0.01"
                                           min="0"
                                           id="price" 
                                           class="block w-full rounded-md border-gray-300 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('price') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">€</span>
                                    </div>
                                </div>
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">
                                    Stock *
                                </label>
                                <div class="mt-1">
                                    <input wire:model.blur="stock_quantity" 
                                           type="number" 
                                           min="0"
                                           id="stock_quantity" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('stock_quantity') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                </div>
                                @error('stock_quantity')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input wire:model="is_active" 
                                       id="is_active" 
                                       type="checkbox" 
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">
                                    Produit actif
                                </label>
                                <p class="text-gray-500">
                                    Les produits actifs sont visibles sur le site.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Optimisation SEO</h3>
                    
                    <div class="space-y-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">
                                Titre SEO
                            </label>
                            <div class="mt-1">
                                <input wire:model.blur="meta_title" 
                                       type="text" 
                                       id="meta_title" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('meta_title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            </div>
                            @error('meta_title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Optionnel. Si vide, le nom du produit sera utilisé.
                            </p>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">
                                Description SEO
                            </label>
                            <div class="mt-1">
                                <textarea wire:model.blur="meta_description" 
                                          id="meta_description" 
                                          rows="3" 
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('meta_description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                          placeholder="Description courte pour les moteurs de recherche..."></textarea>
                            </div>
                            @error('meta_description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Optionnel. Maximum 500 caractères.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Images du produit</h3>
                    
                    <div class="space-y-6">
                        <!-- Upload New Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ajouter des images
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Sélectionner des fichiers</span>
                                            <input wire:model="images" id="images" name="images" type="file" class="sr-only" multiple accept="image/*">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, WebP jusqu'à 2MB chacune
                                    </p>
                                </div>
                            </div>
                            @error('images.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Loading indicator -->
                            <div wire:loading wire:target="images" class="mt-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Upload en cours...
                                </div>
                            </div>
                        </div>

                        <!-- Preview New Images -->
                        @if(!empty($images))
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Nouvelles images à ajouter</h4>
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                    @foreach($images as $index => $image)
                                        <div class="relative group">
                                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                                                @if($image->temporaryUrl())
                                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <button type="button" 
                                                    wire:click="removeImage({{ $index }})"
                                                    class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Existing Images -->
                        @if((is_array($existingImages) && count($existingImages) > 0) || (is_object($existingImages) && $existingImages->isNotEmpty()))
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Images existantes</h4>
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                    @foreach($existingImages as $image)
                                        <div class="relative group">
                                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 {{ $main_image_id === $image['id'] ? 'ring-2 ring-indigo-500' : '' }}">
                                                <img src="{{ $image['url'] ?? '/storage/' . $image['path'] }}" 
                                                     alt="{{ $image['alt'] ?? 'Product image' }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                            
                                            <!-- Image principale badge -->
                                            @if($main_image_id === $image['id'])
                                                <div class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                                                    Principale
                                                </div>
                                            @endif
                                            
                                            <!-- Actions -->
                                            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                @if($main_image_id !== $image['id'])
                                                    <button type="button" 
                                                            wire:click="setMainImage('{{ $image['id'] }}')"
                                                            class="bg-indigo-600 text-white rounded-full p-1"
                                                            title="Définir comme image principale">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                <button type="button" 
                                                        wire:click="removeExistingImage('{{ $image['id'] }}')"
                                                        class="bg-red-600 text-white rounded-full p-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <p class="truncate">{{ $image['original_name'] ?? $image['alt'] ?? 'Image' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Variants (Tailles/Couleurs) -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Variants (Tailles et Couleurs)</h3>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model.live="has_variants" 
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Ce produit a des variants</span>
                        </label>
                    </div>
                    
                    @if($has_variants)
                        <div class="space-y-4">
                            <!-- En-tête des variants -->
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-600">Gérez les différentes combinaisons de tailles et couleurs pour ce produit.</p>
                                <button type="button" 
                                        wire:click="addVariant" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ajouter un variant
                                </button>
                            </div>
                            
                            <!-- Liste des variants -->
                            @if(!empty($variants))
                                <div class="space-y-4">
                                    @foreach($variants as $index => $variant)
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <div class="flex items-center justify-between mb-4">
                                                <h4 class="text-sm font-medium text-gray-900">Variant {{ $index + 1 }}</h4>
                                                <button type="button" 
                                                        wire:click="removeVariant({{ $index }})" 
                                                        class="text-red-600 hover:text-red-800">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                                <!-- Taille -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Taille</label>
                                                    <select wire:model="variants.{{ $index }}.size_id" 
                                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        <option value="">Aucune taille</option>
                                                        @foreach($availableSizes as $size)
                                                            <option value="{{ $size->id }}">{{ $size->name }} ({{ $size->label }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Couleur -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Couleur</label>
                                                    <select wire:model="variants.{{ $index }}.color_id" 
                                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        <option value="">Aucune couleur</option>
                                                        @foreach($availableColors as $color)
                                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Stock -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                                    <input type="number" 
                                                           wire:model="variants.{{ $index }}.stock_quantity" 
                                                           min="0" 
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                </div>
                                                
                                                <!-- Prix spécifique (optionnel) -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix spécifique (€)</label>
                                                    <input type="number" 
                                                           wire:model="variants.{{ $index }}.price" 
                                                           step="0.01" 
                                                           min="0" 
                                                           placeholder="Prix du produit"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                </div>
                                            </div>
                                            
                                            <!-- Affichage du SKU généré -->
                                            @if(!empty($variants[$index]['size_id']) || !empty($variants[$index]['color_id']))
                                                <div class="mt-3 text-xs text-gray-500">
                                                    SKU: {{ $variants[$index]['sku'] ?? 'Généré automatiquement' }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun variant</h3>
                                    <p class="mt-1 text-sm text-gray-500">Commencez par ajouter un variant pour ce produit.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Produit simple</h3>
                            <p class="mt-1 text-sm text-gray-500">Ce produit n'a pas de variants. Utilisez les champs de base ci-dessus.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
                
        <!-- Actions -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <span wire:loading.remove wire:target="save">
                        {{ $isEditing ? 'Modifier' : 'Créer' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sauvegarde...
                    </span>
                </button>
                
                <a href="{{ route('admin.products.index') }}" 
                   class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Annuler
                </a>
            </div>
        </div>
    </form>
</div>
