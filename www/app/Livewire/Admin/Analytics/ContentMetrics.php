<?php

namespace App\Livewire\Admin\Analytics;

use App\Models\News;
use App\Models\User;
use App\Enums\NewsStateEnum;
use App\Enums\UserRoleEnum;
use Livewire\Component;
use Livewire\WithPagination;

class ContentMetrics extends Component
{
    use WithPagination;

    public $search = '';
    public $tab = 'news'; // 'news', 'columnists'

    protected $queryString = ['tab'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
        $this->resetPage();
    }

    public function render()
    {
        $data = [];
        
        if ($this->tab === 'news') {
            // Conta as visualizações e cliques únicos para Notícias
            $data['news'] = News::where('state', NewsStateEnum::PUBLISHED->value)
                ->when($this->search, function($q) {
                    $q->where('title', 'like', "%{$this->search}%");
                })
                ->withCount([
                    'audienceViews as total_views',
                    'audienceViews as unique_views' => function($query) {
                        $query->select(\Illuminate\Support\Facades\DB::raw('count(distinct visitor_profile_id)'));
                    }
                ])
                ->orderByDesc('total_views')
                ->paginate(15);
        } else {
            // Conta as visualizações e cliques únicos para Autores
            $data['columnists'] = User::where('role', UserRoleEnum::COLUMNIST->value)
                ->when($this->search, function($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                })
                ->withCount([
                    'audienceViews as total_views',
                    'audienceViews as unique_views' => function($query) {
                        $query->select(\Illuminate\Support\Facades\DB::raw('count(distinct visitor_profile_id)'));
                    }
                ])
                ->orderByDesc('total_views')
                ->paginate(15);
        }

        return view('livewire.admin.analytics.content-metrics', $data)->layout('layouts.admin');
    }
}
