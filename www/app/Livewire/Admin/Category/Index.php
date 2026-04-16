<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $name;
    public $description;
    public $theme_color = '#0056b3'; // Padrão azul moderno portal
    public $editingId = null;
    public $confirmingDeletionId = null;

    public function mount()
    {
        $this->authorizeAdminOrManager();
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:categories,name,' . $this->editingId,
            'theme_color' => 'required|string',
            'description' => 'nullable|string'
        ];
    }

    public function edit($id)
    {
        $this->authorizeAdminOrManager();
        $record = Category::findOrFail($id);
        $this->editingId = $record->id;
        $this->name = $record->name;
        $this->description = $record->description;
        $this->theme_color = $record->theme_color;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'description', 'theme_color', 'editingId']);
        $this->theme_color = '#0056b3';
    }

    public function save()
    {
        $this->authorizeAdminOrManager();

        $this->validate();

        if ($this->editingId) {
            $category = Category::findOrFail($this->editingId);
            $message = 'Categoria atualizada com sucesso no mapeamento global.';
        } else {
            $category = new Category();
            $message = 'Categoria incorporada ao mapeamento de precedência do Roteador global.';
        }

        $category->name = $this->name;
        // Observers detectam possíveis colisões com Notícias já gravadas e tratam por baixo dos panos
        $category->slug = Str::slug($this->name);
        $category->description = $this->description;
        $category->theme_color = $this->theme_color;
        $category->save();

        session()->flash('message', $message);
        
        $this->cancelEdit();
    }

    private function authorizeAdminOrManager()
    {
        $role = auth()->user()->role;
        if (!in_array($role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::MANAGER->value])) {
            abort(403, 'Ação não autorizada pelo Sanctum.');
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletionId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletionId = null;
    }

    public function deleteCategory()
    {
        $this->authorizeAdminOrManager();

        $id = $this->confirmingDeletionId;
        $category = Category::findOrFail($id);

        if ($category->news()->exists()) {
            session()->flash('error', 'Ação Bloqueada: Fio Condutor Estrutural. Esta categoria abriga matérias vinculadas. Mova as matérias para outras categorias antes de varrê-la do mapa.');
            $this->cancelDelete();
            return;
        }

        $category->delete();
        session()->flash('message', 'Estrutura categorizada varrida com sucesso.');
        $this->cancelDelete();
    }

    public function render()
    {
        return view('livewire.admin.category.index', [
            'categories' => Category::paginate(10)
        ])->layout('layouts.admin');
    }
}
