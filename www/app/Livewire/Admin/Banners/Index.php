<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $image, $target_url, $news_id;
    public $active_days = [];
    public $active_hours = [];
    public $is_active = true;

    public $search = '';
    
    // Live Search Dinâmico
    public $newsSearch = '';
    public $newsSearchResults = [];

    public function updatedNewsSearch()
    {
        // Reseta o vínculo se limpar o campo
        if (trim($this->newsSearch) === '') {
            $this->news_id = null;
            $this->newsSearchResults = [];
            return;
        }

        if (strlen($this->newsSearch) >= 3) {
            $this->newsSearchResults = News::where('title', 'like', "%{$this->newsSearch}%")
                ->select('id', 'title')
                ->latest('published_at')
                ->limit(8)
                ->get()
                ->toArray();
        } else {
            $this->newsSearchResults = [];
        }
    }

    public function selectNews($id, $title)
    {
        $this->news_id = $id;
        $this->newsSearch = $title;
        $this->newsSearchResults = [];
    }

    public function render()
    {
        $banners = Banner::when($this->search, function($q) {
                $q->where('title', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);
        return view('livewire.admin.banners.index', [
            'banners' => $banners
        ])->layout('layouts.admin');
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'image' => 'required|image|max:2048',
        ]);

        $path = $this->image->store('banners', 'public');

        Banner::create([
            'title' => $this->title,
            'image_path' => $path,
            'target_url' => $this->target_url,
            'news_id' => $this->news_id,
            'active_days' => $this->active_days,
            'active_hours' => $this->active_hours,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['title', 'image', 'target_url', 'news_id', 'active_days', 'active_hours', 'is_active', 'newsSearch', 'newsSearchResults']);
        session()->flash('message', 'Banner programado com sucesso!');
    }

    public function toggleStatus($id)
    {
        $b = Banner::find($id);
        $b->is_active = !$b->is_active;
        $b->save();
    }
}
