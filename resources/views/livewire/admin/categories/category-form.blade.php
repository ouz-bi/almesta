<div>
    <form wire:submit="save">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    {{ $isEditing ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    {{ $isEditing ? 'Modifiez les informations de la catégorie' : 'Créez une nouvelle catégorie de produits' }}
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nom de la catégorie *
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

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <div class="mt-1">
                                <textarea wire:model.blur="description" 
                                          id="description" 
                                          rows="4" 
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                          placeholder="Description de la catégorie..."></textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Optionnel. Maximum 1000 caractères.
                            </p>
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
                                    Catégorie active
                                </label>
                                <p class="text-gray-500">
                                    Les catégories actives sont visibles sur le site.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
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
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
