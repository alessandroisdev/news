<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $theme_color = '#0056b3'; // Padrão azul moderno portal

    protected $rules = [
        'name' => 'required|min:3|unique:categories,name',
        'theme_color' => 'required|string',
        'description' => 'nullable|string'
    ];

    public function save()
    {
        $this->authorizeAdminOrManager();

        $this->validate();

        $category = new Category();
        $category->name = $this->name;
        // Observers detectam possíveis colisões com Notícias já gravadas e tratam por baixo dos panos
        $category->slug = Str::slug($this->name);
        $category->description = $this->description;
        $category->theme_color = $this->theme_color;
        $category->save();

        session()->flash('message', 'Categoria incorporada ao mapeamento de precedência do Roteador global.');
        
        $this->reset(['name', 'description', 'theme_color']);
        $this->theme_color = '#0056b3';
    }

    private function authorizeAdminOrManager()
    {
        $role = auth()->user()->role;
        if (!in_array($role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::MANAGER->value])) {
            abort(403, 'Ação não autorizada pelo Sanctum.');
        }
    }

    public function render()
    {
        return view('livewire.admin.category.index', [
            'categories' => Category::paginate(10)
        ])->layout('layouts.admin');
    }
}
