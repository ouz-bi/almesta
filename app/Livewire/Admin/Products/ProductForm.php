<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductForm extends Component
{
    use WithFileUploads;

    public $product;
    public $name = '';
    public $sku = '';
    public $reference = '';
    public $description = '';
    public $price = '';
    public $stock_quantity = 0;
    public $category_id = '';
    public $is_active = true;
    public $meta_title = '';
    public $meta_description = '';
    
    public $images = [];
    public $existingImages;
    public $main_image_id = '';
    public $has_variants = false;
    
    // Variants
    public $variants = [];
    public $availableSizes = [];
    public $availableColors = [];
    
    public $isEditing = false;

    public function __construct()
    {
        $this->existingImages = collect([]);
    }

    private function ensureExistingImagesIsCollection()
    {
        if (!$this->existingImages instanceof \Illuminate\Support\Collection) {
            $this->existingImages = collect($this->existingImages ?? []);
        }
    }
    
    private function loadExistingVariants()
    {
        $this->variants = $this->product->productVariants()
            ->with(['size', 'color'])
            ->get()
            ->map(function($variant) {
                return [
                    'id' => $variant->id,
                    'size_id' => $variant->size_id,
                    'color_id' => $variant->color_id,
                    'stock_quantity' => $variant->stock_quantity,
                    'price' => $variant->price,
                    'sku' => $variant->sku,
                ];
            })->toArray();
    }
    
    private function loadAvailableSizesAndColors()
    {
        $this->availableSizes = \App\Models\Size::active()->byCategory('clothing')->ordered()->get();
        $this->availableColors = \App\Models\Color::active()->ordered()->get();
    }
    
    public function addVariant()
    {
        $this->variants[] = [
            'id' => null,
            'size_id' => null,
            'color_id' => null,
            'stock_quantity' => 0,
            'price' => null,
            'sku' => $this->sku . '-VAR' . (count($this->variants) + 1),
        ];
    }
    
    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }
    
    public function setMainImage($imageId)
    {
        $this->main_image_id = $imageId;
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->isEditing ? $this->product->id : null)
            ],
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($this->isEditing ? $this->product->id : null)
            ],
            'reference' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'reference')->ignore($this->isEditing ? $this->product->id : null)
            ],
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'stock_quantity' => 'required|integer|min:0|max:99999',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'has_variants' => 'boolean',
            'main_image_id' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|max:2048', // 2MB max per image
            'variants.*.size_id' => 'nullable|exists:sizes,id',
            'variants.*.color_id' => 'nullable|exists:colors,id',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.price' => 'nullable|numeric|min:0',
        ];
    }

    protected $validationAttributes = [
        'name' => 'nom',
        'sku' => 'SKU',
        'reference' => 'référence',
        'description' => 'description',
        'price' => 'prix',
        'stock_quantity' => 'stock',
        'category_id' => 'catégorie',
        'is_active' => 'statut',
        'meta_title' => 'titre SEO',
        'meta_description' => 'description SEO',
        'images.*' => 'image',
    ];

    public function mount($product = null)
    {
        if ($product) {
            $this->product = $product;
            $this->isEditing = true;
            $this->name = $product->name;
            $this->sku = $product->sku ?? '';
            $this->reference = $product->reference;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->stock_quantity = $product->stock_quantity;
            $this->category_id = $product->category_id;
            $this->is_active = $product->is_active;
            $this->has_variants = $product->has_variants;
            $this->main_image_id = $product->main_image_id ?? '';
            $this->meta_title = $product->meta_title;
            $this->meta_description = $product->meta_description;
            $this->existingImages = collect($product->images ?? []);
            
            // Charger les variants existants
            $this->loadExistingVariants();
        } else {
            $this->product = new Product();
            // Générer un SKU automatique pour les nouveaux produits
            $this->sku = 'SKU-' . strtoupper(Str::random(8));
            // Initialiser comme collection vide pour les nouveaux produits
            $this->existingImages = collect([]);
        }
        
        // Charger les tailles et couleurs disponibles
        $this->loadAvailableSizesAndColors();
    }

    public function updatedName()
    {
        if (!$this->isEditing && empty($this->meta_title)) {
            $this->meta_title = $this->name;
        }
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function removeExistingImage($imageId)
    {
        // Supprimer l'image de la collection existante
        $this->existingImages = $this->existingImages->filter(function($image) use ($imageId) {
            return $image['id'] !== $imageId;
        })->values();

        // Si on est en mode édition, supprimer aussi du produit
        if ($this->isEditing) {
            $images = collect($this->product->images ?? [])->filter(function($image) use ($imageId) {
                return $image['id'] !== $imageId;
            })->values()->toArray();

            $this->product->update(['images' => $images]);
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'sku' => $this->sku,
                'reference' => $this->reference ?: null,
                'description' => $this->description,
                'price' => $this->price,
                'stock_quantity' => $this->stock_quantity,
                'category_id' => $this->category_id,
                'is_active' => $this->is_active,
                'has_variants' => $this->has_variants,
                'main_image_id' => $this->main_image_id ?: null,
                'meta_title' => $this->meta_title ?: $this->name,
                'meta_description' => $this->meta_description,
                'slug' => Str::slug($this->name),
            ];

            // Gérer l'upload des images avant de sauvegarder
            $data['images'] = $this->handleImageUploads();
            
            if ($this->isEditing) {
                // Mise à jour d'un produit existant
                $this->product->update($data);
                $message = 'Produit modifié avec succès.';
                $this->dispatch('product-updated');
            } else {
                // Création d'un nouveau produit
                $this->product = Product::create($data);
                $message = 'Produit créé avec succès.';
                $this->dispatch('product-created');
            }
            
            // Gérer les variants
            $this->handleVariants();
                
            session()->flash('success', $message);

            // Réinitialiser les images uploadées
            $this->images = [];

            // Rediriger vers la liste
            return redirect()->route('admin.products.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de la sauvegarde: ' . $e->getMessage());
            \Log::error('Erreur lors de la sauvegarde de produit: ' . $e->getMessage());
        }
    }

    private function handleImageUploads()
    {
        $uploadedImages = [];

        // Traiter les nouvelles images uploadées
        if (!empty($this->images)) {
            foreach ($this->images as $index => $image) {
                // Générer un nom de fichier sécurisé
                $extension = $image->getClientOriginalExtension();
                $filename = time() . '_' . $index . '_' . Str::slug($this->name) . '.' . $extension;
                $path = $image->storeAs('products', $filename, 'public');

                $uploadedImages[] = [
                    'id' => uniqid(),
                    'path' => $path,
                    'url' => '/storage/' . $path,
                    'alt' => $this->name,
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                ];
            }
        }

        // Fusionner avec les images existantes
        $this->ensureExistingImagesIsCollection();
        $existingImages = $this->existingImages->toArray();
        $allImages = array_merge($existingImages, $uploadedImages);
        
        // Retourner null si aucune image, sinon le tableau des images
        return empty($allImages) ? null : $allImages;
    }
    
    private function handleVariants()
    {
        if (!$this->has_variants || empty($this->variants)) {
            return;
        }
        
        // Supprimer les variants existants si on est en mode édition
        if ($this->isEditing) {
            $this->product->productVariants()->delete();
        }
        
        // Créer les nouveaux variants
        foreach ($this->variants as $variantData) {
            if (empty($variantData['size_id']) && empty($variantData['color_id'])) {
                continue; // Skip empty variants
            }
            
            \App\Models\ProductVariant::create([
                'product_id' => $this->product->id,
                'size_id' => $variantData['size_id'] ?: null,
                'color_id' => $variantData['color_id'] ?: null,
                'sku' => $variantData['sku'] ?: $this->generateVariantSku($variantData),
                'price' => $variantData['price'] ?: null,
                'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                'is_active' => true,
            ]);
        }
    }
    
    private function generateVariantSku($variantData)
    {
        $sku = $this->sku;
        
        if (!empty($variantData['size_id'])) {
            $size = \App\Models\Size::find($variantData['size_id']);
            if ($size) {
                $sku .= '-' . $size->name;
            }
        }
        
        if (!empty($variantData['color_id'])) {
            $color = \App\Models\Color::find($variantData['color_id']);
            if ($color) {
                $sku .= '-' . strtoupper(substr($color->slug, 0, 3));
            }
        }
        
        return $sku;
    }

    public function render()
    {
        // S'assurer que existingImages est toujours une collection
        $this->ensureExistingImagesIsCollection();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.products.product-form', [
            'categories' => $categories
        ]);
    }
}
