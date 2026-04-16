<?php

namespace App\Livewire\Admin\Analytics;

use App\Models\VisitorProfile;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $totalVisitors = VisitorProfile::count();
        $activeLast24h = VisitorProfile::where('last_active_at', '>=', now()->subDay())->count();

        // Agrupamentos Simples
        $devices = VisitorProfile::select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->pluck('total', 'device_type')->toArray();

        $browsers = VisitorProfile::select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->pluck('total', 'browser')->toArray();

        $locations = VisitorProfile::select('location_data', DB::raw('count(*) as total'))
            ->whereNotNull('location_data')
            ->where('location_data', '!=', 'Unknown')
            ->groupBy('location_data')
            ->orderBy('total', 'desc')
            ->limit(8)
            ->pluck('total', 'location_data')->toArray();

        // Processamento Pesado Lógico de Score
        // Em um sistema real colossal faríamos via job no redis, mas o Collection map resume bem aqui.
        $profiles = VisitorProfile::whereNotNull('preferences_score')->get();
        $globalScores = [];
        
        foreach ($profiles as $p) {
            if (is_array($p->preferences_score)) {
                foreach ($p->preferences_score as $catId => $score) {
                    $globalScores[$catId] = ($globalScores[$catId] ?? 0) + $score;
                }
            }
        }
        
        arsort($globalScores);
        $topCategoriesIds = array_slice(array_keys($globalScores), 0, 5);
        $topCategoriesData = Category::whereIn('id', $topCategoriesIds)->get()->keyBy('id');
        
        $topInterests = [];
        foreach(array_slice($globalScores, 0, 5, true) as $id => $val) {
            if(isset($topCategoriesData[$id])) {
                $topInterests[$topCategoriesData[$id]->name] = $val;
            }
        }

        return view('livewire.admin.analytics.dashboard', [
            'totalVisitors' => $totalVisitors,
            'activeLast24h' => $activeLast24h,
            'devices' => $devices,
            'browsers' => $browsers,
            'locations' => $locations,
            'topInterests' => $topInterests,
        ])->layout('layouts.admin');
    }
}
