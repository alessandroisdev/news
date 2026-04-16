<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\News;
use App\Models\Newsletter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Builder extends Component
{
    public Newsletter $newsletter;
    
    public $searchNews = '';
    public $selectedNews = [];
    
    // Configurações do E-mail Vindo do Model
    public $subject;
    public $title;
    public $body;

    public function mount(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
        $this->subject = $newsletter->subject;
        $this->title = $newsletter->title;
        $this->body = $newsletter->body;
        
        // Puxa as matérias já pinadas de edições salvas
        $this->selectedNews = $this->newsletter->news()->pluck('news_id')->toArray();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['subject', 'title', 'body'])) {
            $this->newsletter->update([
                $propertyName => $this->$propertyName
            ]);
        }
    }

    public function toggleNews($newsId)
    {
        if (in_array($newsId, $this->selectedNews)) {
            $this->selectedNews = array_diff($this->selectedNews, [$newsId]);
        } else {
            // Limite tático de 5 artigos pra não cair em SPAM gigante
            if (count($this->selectedNews) < 5) {
                $this->selectedNews[] = $newsId;
            } else {
                session()->flash('warning', 'Máximo de 5 matérias por expedição para manter alta taxa de entrega e não cair no SPAM.');
                return;
            }
        }
        
        // Refletindo instantaneamente no BD
        $this->newsletter->news()->sync($this->selectedNews);
    }
    
    public function dispatchCampaign()
    {
        $this->validate([
            'subject' => 'required|min:5',
            'selectedNews' => 'required|array|min:1'
        ]);

        if ($this->newsletter->status !== 'draft') {
            return;
        }

        $this->newsletter->update(['status' => 'sending']);
        
        // Despacha Job Assíncrono para consumir no fundo...
        \App\Jobs\ProcessNewsletterDispatch::dispatch($this->newsletter->id);

        session()->flash('message', 'Acervo enviado para as Turbinas de Disparo com Sucesso! Acompanhe o Status.');
        return redirect()->route('admin.newsletter.index');
    }

    public function render()
    {
        $searchQuery = trim($this->searchNews);
        
        $availableNews = News::where('state', \App\Enums\NewsStateEnum::PUBLISHED->value)
                             ->when($searchQuery, function($q) use ($searchQuery) {
                                 $q->where('title', 'like', '%' . $searchQuery . '%');
                             })
                             ->orderByDesc('published_at')
                             ->take(8)
                             ->get();
                             
        // Carrega noticias já pinadas com toda relaçao pra renderizar preview na hora
        $pinnedNews = News::whereIn('id', $this->selectedNews)->orderByRaw('FIELD(id, ' . implode(',', empty($this->selectedNews) ? [0] : $this->selectedNews) . ')')->get();

        return view('livewire.admin.newsletter.builder', [
            'availableNews' => $availableNews,
            'pinnedNews' => $pinnedNews
        ])->layout('layouts.admin');
    }
}
