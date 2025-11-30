<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductsIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    public $showDeleteModal = false;
    public $productToDelete = null;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function confirmDelete($productId)
    {
        $this->productToDelete = $productId;
        $this->showDeleteModal = true;
    }
    
    public function deleteProduct()
    {
        if ($this->productToDelete) {
            $product = Product::find($this->productToDelete);
            
            if ($product) {
                // Supprimer les images du produit si nécessaire
                // TODO: Implémenter la suppression des images
                
                $product->delete();
                session()->flash('success', 'Produit supprimé avec succès.');
            }
        }
        
        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }
    
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }
    
    #[On('product-created')]
    #[On('product-updated')]
    public function refreshProducts()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        $products = Product::query()
            ->with(['category'])
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('reference', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->statusFilter !== '', function($query) {
                if ($this->statusFilter === 'active') {
                    $query->where('is_active', true);
                } elseif ($this->statusFilter === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($this->statusFilter === 'in_stock') {
                    $query->where('stock_quantity', '>', 0);
                } elseif ($this->statusFilter === 'out_of_stock') {
                    $query->where('stock_quantity', '<=', 0);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.admin.products.products-index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
