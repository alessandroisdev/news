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
    public $name;
    public $email;
    public $subscription_status;

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

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->subscription_status = $user->subscription_status;
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

    public function deleteUser($id)
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        if ($id == auth()->id()) {
            session()->flash('error', 'Suicídio digital não permitido. Você não pode deletar a si mesmo.');
            return;
        }

        $user = User::findOrFail($id);

        if ($user->news()->exists()) {
            // Em vez de quebrar o banco (Foreign Key Constraints), blindamos com aviso
            session()->flash('error', 'Ops! ' . $user->name . ' possui matérias ligadas ao nome dele. Troque o status dele para Inativo em vez de deletar para não desfigurar o jornal.');
            return;
        }

        $user->delete();
        session()->flash('message', 'O registro da identidade foi varrido do banco permanentemente.');
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
