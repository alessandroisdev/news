<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Enums\UserRoleEnum;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $roleFilter = '';
    public $editingId = null;
    public $confirmingDeletionId = null;
    public $name;
    public $email;
    public $subscription_status;

    public $isCreating = false;
    public $newName = '';
    public $newEmail = '';
    public $newRole = 'subscriber';
    public $newPassword = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->editingId,
            'subscription_status' => 'required|string',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updateRole($userId, $newRole)
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403, 'Apenas Administradores podem alterar permissões.');
        }

        // Proteger contra remover o próprio admin
        if ($userId == auth()->id()) {
            session()->flash('error', 'Você não pode alterar seu próprio privilégio global.');
            return;
        }

        $user = User::findOrFail($userId);
        $user->role = $newRole;
        $user->save();

        session()->flash('message', 'Nível de acesso de ' . $user->name . ' atualizado para ' . $newRole . '.');
    }

    public function create()
    {
        $this->cancelEdit();
        $this->isCreating = true;
        $this->reset(['newName', 'newEmail', 'newRole', 'newPassword']);
    }

    public function cancelCreate()
    {
        $this->isCreating = false;
    }

    public function store()
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $this->validate([
            'newName' => 'required|string|max:255',
            'newEmail' => 'required|email|unique:users,email',
            'newRole' => 'required|string',
            'newPassword' => 'required|min:6', // Minimum length
        ]);

        $user = new User();
        $user->name = $this->newName;
        $user->email = $this->newEmail;
        $user->role = $this->newRole;
        $user->password = \Illuminate\Support\Facades\Hash::make($this->newPassword);
        
        $colors = ['#0d6efd', '#6f42c1', '#d63384', '#fd7e14', '#198754', '#0dcaf0'];
        $user->theme_color = $colors[array_rand($colors)];
        $user->subscription_status = 'active';

        $user->save();

        session()->flash('message', 'A identidade de ' . $user->name . ' foi forjada com a patente de ' . strtoupper($user->role) . '!');
        $this->cancelCreate();
    }

    public function edit($id)
    {
        $this->cancelCreate();
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->subscription_status = $user->subscription_status ?? 'inactive';
    }

    public function cancelEdit()
    {
        $this->reset(['editingId', 'name', 'email', 'subscription_status']);
    }

    public function save()
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $this->validate();

        $user = User::findOrFail($this->editingId);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->subscription_status = $this->subscription_status;
        $user->save();

        session()->flash('message', 'Dados da identidade ' . $user->name . ' atualizados com sucesso.');
        $this->cancelEdit();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletionId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletionId = null;
    }

    public function deleteUser()
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $id = $this->confirmingDeletionId;

        if ($id == auth()->id()) {
            session()->flash('error', 'Suicídio digital não permitido. Você não pode deletar a si mesmo.');
            $this->cancelDelete();
            return;
        }

        $user = User::findOrFail($id);

        if ($user->news()->exists()) {
            session()->flash('error', 'Ops! ' . $user->name . ' possui matérias ligadas ao nome dele. Troque o status dele para Inativo em vez de deletar para não desfigurar o jornal.');
            $this->cancelDelete();
            return;
        }

        $user->delete();
        session()->flash('message', 'O registro da identidade foi varrido do banco permanentemente.');
        $this->cancelDelete();
    }

    public function render()
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $query = User::query()->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        return view('livewire.admin.users.index', [
            'users' => $query->paginate(15)
        ])->layout('layouts.admin');
    }
}
