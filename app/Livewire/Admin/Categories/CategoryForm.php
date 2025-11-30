<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Validation\Rule;

class CategoryForm extends Component
{
    public $category;
    public $name = '';
    public $description = '';
    public $is_active = true;
    
    public $isEditing = false;
    
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->isEditing ? $this->category->id : null)
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];
    }
    
    protected $validationAttributes = [
        'name' => 'nom',
        'description' => 'description',
        'is_active' => 'statut',
    ];
    
    public function mount($category = null)
    {
        if ($category) {
            $this->category = $category;
            $this->isEditing = true;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->is_active = $category->is_active;
        } else {
            $this->category = new Category();
        }
    }
    
    public function save()
    {
        $this->validate();
        
        try {
            if ($this->isEditing) {
                // Mise à jour d'une catégorie existante
                $this->category->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);
                $message = 'Catégorie modifiée avec succès.';
                $this->dispatch('category-updated');
            } else {
                // Création d'une nouvelle catégorie
                Category::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);
                $message = 'Catégorie créée avec succès.';
                $this->dispatch('category-created');
            }
            
            session()->flash('success', $message);
            
            // Rediriger vers la liste
            return redirect()->route('admin.categories.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de la sauvegarde: ' . $e->getMessage());
            \Log::error('Erreur lors de la sauvegarde de catégorie: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.admin.categories.category-form');
    }
}
