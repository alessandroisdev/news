<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Trash extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function restoreUser($id)
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        session()->flash('message', 'A identidade de ' . $user->name . ' foi restaurada do exílio e voltou a ser listada no sistema principal!');
    }

    public function render()
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $trashedUsers = User::onlyTrashed()->latest('deleted_at')->paginate(15);

        return view('livewire.admin.users.trash', [
            'users' => $trashedUsers
        ])->layout('layouts.admin');
    }
}
