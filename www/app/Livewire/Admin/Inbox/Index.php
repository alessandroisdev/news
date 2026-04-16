<?php

namespace App\Livewire\Admin\Inbox;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $viewingMessage = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewMessage($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->update(['is_read' => true]);
        $this->viewingMessage = $msg;
    }

    public function closeMessage()
    {
        $this->viewingMessage = null;
    }

    public function deleteMessage($id)
    {
        ContactMessage::findOrFail($id)->delete();
        if ($this->viewingMessage && $this->viewingMessage->id === $id) {
            $this->closeMessage();
        }
        session()->flash('success', 'Mensagem movida para a lixeira.');
    }

    public function render()
    {
        $messages = ContactMessage::when($this->search, function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('subject', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.inbox.index', [
            'messages' => $messages
        ])->layout('layouts.admin');
    }
}
