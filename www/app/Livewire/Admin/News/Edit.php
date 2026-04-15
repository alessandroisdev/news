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

class Edit extends Component
{
    use WithFileUploads;

    public News $newsModel;
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

    public function mount(News $news)
    {
        $this->newsModel = $news;
        $this->title = $news->title;
        $this->content = $news->content;
        $this->category_id = $news->category_id;
    }

    public function save()
    {
        $this->validate();

        $user = Auth::user();
        
        // Mantém a máquina de estados existente ou aprova instantaneamente se for um Admin alterando.
        if ($user->role === UserRoleEnum::ADMIN->value || $user->role === UserRoleEnum::MANAGER->value) {
            $this->newsModel->state = NewsStateEnum::PUBLISHED->value;
            if (!$this->newsModel->published_at) {
                $this->newsModel->published_at = now();
            }
        }

        $this->newsModel->title = $this->title;
        $this->newsModel->slug = Str::slug($this->title);
        $this->newsModel->content = $this->content;
        $this->newsModel->category_id = $this->category_id;

        if ($this->cover_image) {
            $filename = $this->cover_image->store('news', 'public');
            $this->newsModel->cover_image = $filename;
        }

        $this->newsModel->save();

        session()->flash('message', 'Matéria atualizada de forma inteligente!');
        return redirect()->route('admin.news.index');
    }

    public function render()
    {
        return view('livewire.admin.news.edit', [
            'categories' => Category::all()
        ])->layout('layouts.admin');
    }
}
