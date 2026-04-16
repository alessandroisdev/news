<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class MediaLibraryModal extends Component
{
    use WithFileUploads, WithPagination;

    public $upload;
    public $search = '';
    public $isOpen = false;
    
    // We can emit back to the caller component or directly dispatch to Alpine
    public $targetContext = 'quill_image'; 

    #[On('open-media-library')]
    public function openModal($context = 'quill_image')
    {
        $this->targetContext = $context;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['upload', 'search']);
    }

    public function updatedUpload()
    {
        $this->validate([
            'upload' => 'max:10240', // 10MB max
        ]);

        $path = $this->upload->store('media', 'public');

        MediaAsset::create([
            'user_id' => Auth::id(),
            'file_name' => $this->upload->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $this->upload->getMimeType(),
            'size' => $this->upload->getSize(),
            'disk' => 'public',
        ]);

        $this->reset('upload');
        session()->flash('message', 'Arquivo carregado com sucesso!');
    }

    public function selectMedia($id)
    {
        $media = MediaAsset::find($id);
        if ($media) {
            $url = Storage::disk($media->disk)->url($media->file_path);
            
            // Return event to Alpine frontend
            $this->dispatch('media-selected', [
                'url' => $url,
                'context' => $this->targetContext,
                'type' => str_starts_with($media->mime_type, 'image') ? 'image' : 'file',
                'name' => $media->file_name
            ]);
            
            $this->closeModal();
        }
    }

    public function render()
    {
        $media = MediaAsset::where('user_id', Auth::id())
            ->when($this->search, function($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(16);

        return view('livewire.admin.media.media-library-modal', [
            'assets' => $media
        ]);
    }
}
