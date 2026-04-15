<div>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h3 class="fw-bolder text-dark mb-0" style="font-family: 'Outfit', sans-serif;">Pauta Jornalística (Edição)</h3>
        <a href="{{ route('admin.news.index') }}" class="btn btn-light border fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
        <div class="card-body p-4 p-md-5">
            <form wire:submit.prevent="save">
                <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-pencil-square me-2"></i>Editando Publicação</h5>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Manchete (H1 Principal)</label>
                    <input type="text" wire:model="title" class="form-control form-control-lg bg-light border-0 shadow-none @error('title') is-invalid @enderror" placeholder="A manchete começa aqui com impacto...">
                    @error('title') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4 g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Editoria SEO (URL Route)</label>
                        <select wire:model="category_id" class="form-select form-select-lg bg-light border-0 shadow-none @error('category_id') is-invalid @enderror">
                            <option value="">Selecione de forma obrigatória...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Status de Publicação</label>
                        <select wire:model="state" class="form-select form-select-lg bg-light border-0 shadow-none @error('state') is-invalid @enderror">
                            @foreach(\App\Enums\NewsStateEnum::cases() as $stateEnum)
                                <option value="{{ $stateEnum->value }}">{{ $stateEnum->label() }}</option>
                            @endforeach
                        </select>
                        @error('state') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary small text-uppercase d-block">Capa Dinâmica (Banner)</label>
                        
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($cover_image)
                                <div class="position-relative rounded-3 overflow-hidden border shadow-sm border-success border-2" style="width: 140px; height: 90px; flex-shrink: 0;">
                                    <img src="{{ $cover_image->temporaryUrl() }}" class="w-100 h-100 object-fit-cover" alt="Nova Capa Preview">
                                    <div class="position-absolute bottom-0 w-100 text-center bg-success text-white py-1" style="font-size: 0.65rem; font-weight: bold;">NOVO PREVIEW</div>
                                </div>
                            @elseif($newsModel->cover_image)
                                <div class="position-relative rounded-3 overflow-hidden shadow-sm border" style="width: 140px; height: 90px; flex-shrink: 0;">
                                    <img src="{{ asset('storage/' . $newsModel->cover_image) }}" class="w-100 h-100 object-fit-cover" alt="Capa atual">
                                    <div class="position-absolute bottom-0 w-100 text-center bg-dark text-white py-1 opacity-75" style="font-size: 0.65rem;">Capa Registrada</div>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                        <input type="file" wire:model="cover_image" class="form-control form-control-lg bg-light border-0 shadow-none @error('cover_image') is-invalid @enderror" accept="image/*">
                        @error('cover_image') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="cover_image" class="text-primary small mt-2 fw-semibold">
                                <span class="spinner-border spinner-border-sm me-1"></span> Renderizando para preview local...
                            </div>
                            <small class="text-muted d-block mt-2">Dica: Deixe este campo vazio caso você queira preservar a imagem de capa original que já existe.</small>
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
                            }
                        });
                        quill.root.innerHTML = content;
                        quill.on('text-change', function () {
                            content = quill.root.innerHTML;
                        });
                    ">
                        <div x-ref="quillEditor" style="min-height: 400px; font-family: 'Inter', sans-serif; font-size: 1.05rem;"></div>
                    </div>
                </div>
                @error('content') <div class="invalid-feedback d-block fw-bold p-2 mb-4">{{ $message }}</div> @enderror

                <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-center bg-light p-4 rounded-3 border">
                    <button type="submit" class="btn btn-success btn-lg fw-bold px-5 border-0 shadow-sm" style="transition: all 0.2s;" onmouseover="this.style.transform='scale(1.02)';" onmouseout="this.style.transform='scale(1)';">
                        <span wire:loading.remove wire:target="save"><i class="bi bi-check2-circle me-1"></i> Atualizar Artigo</span>
                        <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Substituindo blocos...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    @endpush
</div>
