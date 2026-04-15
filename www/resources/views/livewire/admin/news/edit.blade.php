<div>
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
                        <label class="form-label fw-bold text-secondary small text-uppercase">Capa Dinâmica</label>
                        <input type="file" wire:model="cover_image" class="form-control form-control-lg bg-light border-0 shadow-none @error('cover_image') is-invalid @enderror" accept="image/*">
                        @error('cover_image') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                        <div wire:loading wire:target="cover_image" class="text-primary small mt-2 fw-semibold">
                            <span class="spinner-border spinner-border-sm me-1"></span> Renderizando para preview local...
                        </div>
                        <small class="text-muted d-block mt-2">Dica: Deixe este campo vazio caso você queira preservar a imagem de capa original vinculada a esta matéria no Glide.</small>
                    </div>
                </div>

                <div class="mb-5 border rounded-3 overflow-hidden bg-light border-light">
                    <!-- Opcional de forma nativa não usar rich text pesado a não sr que seja exigido. Focamos no purismo -->
                    <div class="bg-white p-2 border-bottom d-flex gap-2">
                        <button class="btn btn-sm btn-light border" type="button"><i class="bi bi-type-bold"></i></button>
                        <button class="btn btn-sm btn-light border" type="button"><i class="bi bi-link-45deg"></i></button>
                        <button class="btn btn-sm btn-light border" type="button"><i class="bi bi-image"></i></button>
                    </div>
                    <textarea wire:model="content" class="form-control border-0 shadow-none" rows="12" style="background-color: #fafbfc; resize: vertical;" placeholder="Desenvolva sua matéria integralmente aqui..."></textarea>
                    @error('content') <div class="invalid-feedback d-block fw-bold p-2">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-center bg-light p-4 rounded-3 border">
                    <button type="submit" class="btn btn-success btn-lg fw-bold px-5 border-0 shadow-sm" style="transition: all 0.2s;" onmouseover="this.style.transform='scale(1.02)';" onmouseout="this.style.transform='scale(1)';">
                        <span wire:loading.remove wire:target="save"><i class="bi bi-check2-circle me-1"></i> Atualizar Artigo</span>
                        <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Substituindo blocos...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
