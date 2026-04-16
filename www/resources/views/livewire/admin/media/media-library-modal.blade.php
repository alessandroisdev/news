<div>
    @if($isOpen)
    <div class="modal-backdrop fade show" style="z-index: 1050;"></div>
    <div class="modal fade show d-block" tabindex="-1" style="z-index: 1055;">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
          <div class="modal-header bg-light border-0 px-4 py-3">
            <h5 class="modal-title fw-bolder text-dark" style="font-family: 'Outfit', sans-serif;">
                <i class="bi bi-collection-play me-2"></i>Biblioteca de Mídia do Workspace
            </h5>
            <button type="button" class="btn-close" wire:click="closeModal"></button>
          </div>
          <div class="modal-body p-0 bg-white">
              
              <!-- Toolbar Topo -->
              <div class="d-flex flex-wrap p-4 bg-white border-bottom gap-3 justify-content-between">
                  <div class="flex-grow-1" style="max-width: 400px;">
                      <div class="input-group">
                          <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                          <input type="text" wire:model.live.debounce.300ms="search" class="form-control bg-light border-start-0 shadow-none ps-0" placeholder="Buscar por nome do arquivo...">
                      </div>
                  </div>
                  
                  <div class="d-flex align-items-center gap-3">
                      <div wire:loading wire:target="upload" class="text-primary small fw-semibold">
                          <span class="spinner-border spinner-border-sm me-1"></span> Fazendo upload do pacote...
                      </div>
                      
                      <div class="position-relative">
                          <input type="file" wire:model="upload" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" accept="image/*,video/*,application/pdf">
                          <button type="button" class="btn btn-primary fw-bold shadow-sm px-4">
                              <i class="bi bi-cloud-arrow-up-fill me-2"></i>Fazer Upload
                          </button>
                      </div>
                  </div>
              </div>

              <!-- Grid de Mídias -->
              <div class="p-4" style="min-height: 400px; background-color: #f8f9fc;">
                  @if(session()->has('message'))
                      <div class="alert alert-success border-0 shadow-sm rounded-3 fw-semibold">
                          <i class="bi bi-check-circle-fill me-2"></i>{{ session('message') }}
                      </div>
                  @endif

                  @if($assets->isEmpty())
                      <div class="text-center py-5">
                          <div class="text-muted mb-3"><i class="bi bi-camera shadow-sm rounded-circle p-4 bg-white fs-1"></i></div>
                          <h5 class="fw-bold text-dark mt-4">Sua biblioteca está vazia</h5>
                          <p class="text-muted">Faça upload de fotos, vídeos ou PDFs para reutilizá-los em suas matérias.</p>
                      </div>
                  @else
                      <div class="row g-3">
                          @foreach($assets as $asset)
                          <div class="col-6 col-md-4 col-lg-3">
                              <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden asset-card" style="cursor: pointer; transition: transform 0.2s;" wire:click="selectMedia({{ $asset->id }})" onmouseover="this.style.transform='scale(1.02)';" onmouseout="this.style.transform='scale(1)';">
                                  <div class="card-img-top bg-light position-relative d-flex align-items-center justify-content-center border-bottom" style="height: 160px;">
                                      @if(str_starts_with($asset->mime_type, 'image'))
                                          <img src="{{ Storage::disk($asset->disk)->url($asset->file_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $asset->file_name }}">
                                      @elseif(str_starts_with($asset->mime_type, 'video'))
                                          <i class="bi bi-play-circle-fill text-danger fs-1"></i>
                                      @else
                                          <i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i>
                                      @endif
                                      <div class="position-absolute bottom-0 end-0 m-2">
                                          <span class="badge bg-dark bg-opacity-75 text-white shadow-sm border" style="font-size: 0.65rem;">{{ round($asset->size / 1024) }} KB</span>
                                      </div>
                                  </div>
                                  <div class="card-body p-2 bg-white">
                                      <p class="card-text small text-truncate fw-semibold text-dark mb-0" title="{{ $asset->file_name }}">{{ $asset->file_name }}</p>
                                      <p class="card-text text-muted" style="font-size: 0.70rem;">{{ $asset->created_at->format('d/m/Y H:i') }}</p>
                                  </div>
                              </div>
                          </div>
                          @endforeach
                      </div>
                      <div class="mt-4">
                          {{ $assets->links() }}
                      </div>
                  @endif
              </div>

          </div>
        </div>
      </div>
    </div>
    @endif
</div>
