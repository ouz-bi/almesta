<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class UsersIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $roleFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    public $showDeleteModal = false;
    public $userToDelete = null;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingRoleFilter()
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
    
    public function toggleUserRole($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->role = $user->role === 'admin' ? 'user' : 'admin';
            $user->save();
            
            session()->flash('success', 'Rôle utilisateur modifié avec succès.');
        }
    }
    
    public function confirmDelete($userId)
    {
        // Empêcher la suppression de son propre compte
        if ($userId === auth()->id()) {
            session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }
        
        $this->userToDelete = $userId;
        $this->showDeleteModal = true;
    }
    
    public function deleteUser()
    {
        if ($this->userToDelete && $this->userToDelete !== auth()->id()) {
            $user = User::find($this->userToDelete);
            
            if ($user) {
                $user->delete();
                session()->flash('success', 'Utilisateur supprimé avec succès.');
            }
        }
        
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }
    
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }
    
    public function render()
    {
        $users = User::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function($query) {
                $query->where('role', $this->roleFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.admin.users.users-index', [
            'users' => $users
        ]);
    }
}
