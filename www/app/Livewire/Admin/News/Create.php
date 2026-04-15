<?php

namespace App\Livewire\Admin\News;

use App\Models\Category;
use App\Models\News;
use App\Enums\NewsStateEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $content;
    public $category_id;
    public $cover_image;

    protected $rules = [
        'title' => 'required|min:5|max:255',
        'content' => 'required|min:20',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|max:4048' // Limite superior pro file system
    ];

    public function mount()
    {
        $this->category_id = Category::first()->id ?? null;
    }

    public function save()
    {
        $this->validate();

        $user = Auth::user();
        $state = NewsStateEnum::DRAFT->value; // O padrão nativo minimalista protegido
        
        // Máquina de Estado automatizada (Admins publicam na hora, jornalistas mandam para fila de revisão)
        if ($user->role === UserRoleEnum::ADMIN->value || $user->role === UserRoleEnum::MANAGER->value) {
            $state = NewsStateEnum::PUBLISHED->value;
        } else {
             $state = NewsStateEnum::REVIEW->value;
        }

        $news = new News();
        $news->title = $this->title;
        // O Observer lidará posteriormente com colisões para manter a pureza das Tags na rota /.
        $news->slug = Str::slug($this->title);
        $news->content = $this->content;
        $news->category_id = $this->category_id;
        $news->author_id = $user->id;
        $news->state = $state;
        
        if ($state === NewsStateEnum::PUBLISHED->value) {
            $news->published_at = now();
        }

        if ($this->cover_image) {
            // Salva na storage/app/public/news para bater perfeitamente localmente com a rota on-the-fly ImageController do escopo
            $filename = $this->cover_image->store('news', 'public');
            // Retira a subpasta se desejar manter mapeado de forma crua, mas vamos manter raw
            $news->cover_image = $filename;
        }

        $news->save();

        session()->flash('message', 'Matéria tramitada na máquina de status (State Machine) e salva com sucesso!');
        return redirect()->route('admin.news.index');
    }

    public function render()
    {
        return view('livewire.admin.news.create', [
            'categories' => Category::all()
        ])->layout('layouts.admin');
    }
}
