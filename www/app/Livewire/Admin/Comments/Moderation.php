<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use App\Models\User;
use App\Enums\UserRoleEnum;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Moderation extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, shadowbanned, hidden

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function hideComment($id)
    {
        Comment::findOrFail($id)->update(['is_hidden' => true]);
        session()->flash('success', 'Mensagem ocultada publicamente.');
    }

    public function approveComment($id)
    {
        Comment::findOrFail($id)->update([
            'is_hidden' => false,
            'is_shadowbanned' => false,
            'shadowban_reason' => null
        ]);
        session()->flash('success', 'Mensagem aprovada e publicada (Retirada do Shadowban).');
    }

    public function deleteComment($id)
    {
        Comment::findOrFail($id)->delete();
        session()->flash('success', 'Comentário DELETADO permanentemente.');
    }

    public function banUser($userId)
    {
        $user = User::findOrFail($userId);
        if(!in_array($user->role, [UserRoleEnum::ADMIN->value, UserRoleEnum::MANAGER->value])) {
            $user->update(['banned_from_comments' => true]);
            session()->flash('success', 'Usuário amordaçado. Ele não poderá mais comentar em nenhuma notícia.');
        } else {
            session()->flash('error', 'Você não pode banir um Gestor/Admin.');
        }
    }

    public function unbanUser($userId)
    {
        User::findOrFail($userId)->update([
            'banned_from_comments' => false,
            'comment_strikes' => 0 // Reseta as 3 vidas
        ]);
        session()->flash('success', 'Usuário perdoado. Ele possui 3 strikes novamente.');
    }

    public function render()
    {
        $user = Auth::user();

        $query = Comment::with(['user', 'news'])
                ->when($this->filter === 'shadowbanned', function($q) {
                    $q->where('is_shadowbanned', true);
                })
                ->when($this->filter === 'hidden', function($q) {
                    $q->where('is_hidden', true);
                });

        // Limita visão se for Colunista (Apenas moderar notícias dele)
        if ($user->role === UserRoleEnum::COLUMNIST->value) {
            $query->whereHas('news', function($q) use ($user) {
                $q->where('author_id', $user->id);
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.admin.comments.moderation', [
            'comments' => $comments
        ])->layout('layouts.admin');
    }
}
