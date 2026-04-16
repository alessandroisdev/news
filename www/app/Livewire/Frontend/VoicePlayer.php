<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class VoicePlayer extends Component
{
    public $content;
    
    public function mount($content)
    {
        // Limpa o HTML do banco transformando em texto puro corrido para os algoritmos sintéticos lerem limpo.
        $this->content = strip_tags(html_entity_decode($content));
    }

    public function render()
    {
        return view('livewire.frontend.voice-player');
    }
}
