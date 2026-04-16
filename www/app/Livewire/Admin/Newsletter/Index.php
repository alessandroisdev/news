<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\Newsletter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';

    public function createDraft()
    {
        $newsletter = Newsletter::create([
            'subject' => 'Nova Edição do Portal',
            'title' => 'Boletim Semanal',
            'status' => 'draft'
        ]);
        
        return redirect()->route('admin.newsletter.builder', $newsletter->id);
    }

    public function delete($id)
    {
        $n = Newsletter::findOrFail($id);
        if ($n->status == 'sending') {
            session()->flash('error', 'Não é possível deletar uma revista que está no motor de envio no momento!');
            return;
        }
        $n->delete();
        session()->flash('message', 'Acervo deletado da plataforma.');
    }

    public function render()
    {
        $newsletters = Newsletter::where('title', 'like', '%' . $this->search . '%')
                                 ->orWhere('subject', 'like', '%' . $this->search . '%')
                                 ->orderByDesc('created_at')
                                 ->paginate(10);
                                 
        return view('livewire.admin.newsletter.index', [
            'newsletters' => $newsletters
        ])->layout('layouts.admin');
    }
}
