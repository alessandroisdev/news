<div>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h3 class="fw-bolder text-dark mb-0" style="font-family: 'Outfit', sans-serif;">Pauta Jornalística</h3>
        <a href="{{ route('admin.news.index') }}" class="btn btn-light border fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
        <div class="card-body p-4 p-md-5">
            <form wire:submit.prevent="save">
                <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-feather me-2"></i>Nova Publicação / Artigo</h5>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Manchete (H1 Principal)</label>
                    <input type="text" wire:model="title" class="form-control form-control-lg bg-light border-0 shadow-none @error('title') is-invalid @enderror" placeholder="A manchete começa aqui com impacto...">
                    @error('title') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4 g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Editoria SEO (URL Route)</label>
                        <select wire:model="category_id" class="form-select form-select-lg bg-light border-0 shadow-none @error('category_id') is-invalid @enderror">
                            <option value="">Selecione de forma obrigatória...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Capa Dinâmica (On-The-Fly Crop)</label>
                        <input type="file" wire:model="cover_image" class="form-control form-control-lg bg-light border-0 shadow-none @error('cover_image') is-invalid @enderror" accept="image/*">
                        @error('cover_image') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                        <div wire:loading wire:target="cover_image" class="text-primary small mt-2 fw-semibold">
                            <span class="spinner-border spinner-border-sm me-1"></span> Realizando bind para crop dinâmico local...
                        </div>
                    </div>
                </div>

                <div class="mb-5 border rounded-3 overflow-hidden bg-white border-light" wire:ignore>
                    <div x-data="{ content: @entangle('content') }" x-init="
                        let quill = new Quill($refs.quillEditor, {
                            theme: 'snow',
                            placeholder: 'Comece a desenvolver sua matéria integralmente aqui...',
                            modules: {
                                toolbar: [
                                    [{ 'header': [2, 3, false] }],
                                    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    ['link', 'image', 'video'],
                                    ['clean']
                                ]
                        });
                        quill.root.innerHTML = content;
                        quill.on('text-change', function () {
                            content = quill.root.innerHTML;
                        });
                        
                        let toolbar = quill.getModule('toolbar');
                        toolbar.addHandler('image', function() {
                            Livewire.dispatch('open-media-library', { context: 'quill_image' });
                        });
                        toolbar.addHandler('video', function() {
                            Livewire.dispatch('open-media-library', { context: 'quill_video' });
                        });
                        
                        // Escutando resposta da biblioteca global
                        window.addEventListener('media-selected', (event) => {
                            let data = event.detail[0] || event.detail;
                            if (data.context === 'quill_image' || data.context === 'quill_video') {
                                let range = quill.getSelection(true) || {index: quill.getLength()};
                                if (data.type === 'image') {
                                    quill.insertEmbed(range.index, 'image', data.url);
                                } else if (data.type === 'file') { // fallback video or file
                                    quill.insertText(range.index, data.name, 'link', data.url);
                                }
                                quill.setSelection(range.index + 1);
                                content = quill.root.innerHTML;
                            }
                        });
                    ">
                        <div x-ref="quillEditor" style="min-height: 400px; font-family: 'Inter', sans-serif; font-size: 1.05rem;"></div>
                    </div>
                </div>
                @error('content') <div class="invalid-feedback d-block fw-bold p-2 mb-4">{{ $message }}</div> @enderror

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-light p-4 rounded-3 border">
                    <div class="me-auto text-muted small d-flex mb-3 mb-md-0">
                        <div class="fs-2 text-primary me-3 lh-1"><i class="bi bi-shield-lock-fill"></i></div>
                        <div>
                            <strong>Modo Automático Role/Abilities:</strong><br>
                            A máquina assíncrona destina o artigo para Rascunho, Pendente de Revisão (se você for apenas Autor) ou publica instantaneamente em milissegundos.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 border-0 shadow-sm" style="transition: all 0.2s;" onmouseover="this.style.transform='scale(1.02)';" onmouseout="this.style.transform='scale(1)';">
                        <span wire:loading.remove wire:target="save">Tramitar Operação</span>
                        <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    @endpush
</div>
