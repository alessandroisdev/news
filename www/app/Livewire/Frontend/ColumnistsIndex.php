<?php

namespace App\Livewire\Frontend;

use App\Models\User;
use App\Enums\UserRoleEnum;
use Livewire\Component;

class ColumnistsIndex extends Component
{
    public function render()
    {
        $columnists = User::where('role', UserRoleEnum::COLUMNIST->value)
            ->withCount(['news' => function ($query) {
                $query->where('state', \App\Enums\NewsStateEnum::PUBLISHED->value);
            }])
            ->orderByDesc('news_count')
            ->get();

        return view('livewire.frontend.columnists-index', [
            'columnists' => $columnists
        ])->layout('layouts.app');
    }
}
