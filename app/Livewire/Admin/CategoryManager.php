<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    use WithPagination;
    
    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $categoryId = null;
    
    public $name = '';
    public $slug = '';
    public $description = '';
    public $parent_id = null;
    public $is_active = true;
    public $sort_order = 0;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',
        'is_active' => 'boolean',
        'sort_order' => 'integer|min:0',
    ];
    
    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function editCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->is_active = $category->is_active;
        $this->sort_order = $category->sort_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $rules = $this->rules;
        
        if ($this->editMode) {
            $rules['slug'] = 'required|string|max:255|unique:categories,slug,' . $this->categoryId;
        }
        
        $this->validate($rules);
        
        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];
        
        if ($this->editMode) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($data);
            session()->flash('success', 'Catégorie mise à jour avec succès.');
        } else {
            Category::create($data);
            session()->flash('success', 'Catégorie créée avec succès.');
        }
        
        $this->closeModal();
    }
    
    public function deleteCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();
        
        session()->flash('success', 'Catégorie supprimée avec succès.');
    }
    
    public function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->parent_id = null;
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetErrorBag();
    }
    
    public function render()
    {
        $categories = Category::with('parent')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->paginate(10);
            
        $parentCategories = Category::whereNull('parent_id')->get();
        
        return view('livewire.admin.category-manager', [
            'categories' => $categories,
            'parentCategories' => $parentCategories
        ]);
    }
}
