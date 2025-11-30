<div id="{{ $modalId }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-4 md:p-6">
            <div class="flex justify-between items-center mb-4 md:mb-6">
                <h3 class="text-xl md:text-2xl font-bold text-gray-900">{{ $title }}</h3>
                <button type="button" onclick="closeModal('{{ $modalId }}')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('checkout.address.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Téléphone *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Adresse ligne 1 *</label>
                        <input type="text" name="address_line1" value="{{ old('address_line1') }}" required
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('address_line1') border-red-500 @enderror">
                        @error('address_line1')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Adresse ligne 2</label>
                        <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Code postal *</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('postal_code') border-red-500 @enderror">
                        @error('postal_code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Ville *</label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                               class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Pays *</label>
                        <select name="country" required
                                class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 @error('country') border-red-500 @enderror">
                            <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                            <option value="Belgique" {{ old('country') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                            <option value="Suisse" {{ old('country') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                            <option value="Luxembourg" {{ old('country') == 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                        </select>
                        @error('country')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                                   class="text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Définir comme adresse par défaut</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('{{ $modalId }}')"
                            class="w-full sm:w-auto px-4 md:px-6 py-2 border border-gray-300 rounded-lg text-sm md:text-base text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto px-4 md:px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm md:text-base font-medium transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
