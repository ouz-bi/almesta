<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class CategoriesIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    public $showDeleteModal = false;
    public $categoryToDelete = null;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];
    
    public function updatingSearch()
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
    
    public function confirmDelete($categoryId)
    {
        $this->categoryToDelete = $categoryId;
        $this->showDeleteModal = true;
    }
    
    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            $category = Category::find($this->categoryToDelete);
            
            if ($category) {
                // Vérifier s'il y a des produits liés
                if ($category->products()->count() > 0) {
                    session()->flash('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
                } else {
                    $category->delete();
                    session()->flash('success', 'Catégorie supprimée avec succès.');
                }
            }
        }
        
        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }
    
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }
    
    #[On('category-created')]
    #[On('category-updated')]
    public function refreshCategories()
    {
        // Force la pagination à se rafraîchir
        $this->resetPage();
    }
    
    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->withCount('products')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.admin.categories.categories-index', [
            'categories' => $categories
        ]);
    }
}
