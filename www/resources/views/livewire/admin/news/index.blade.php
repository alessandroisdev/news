<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Gestão de Notícias</h3>
            <p class="text-muted small mb-0">Controle completo do fluxo de publicações do portal.</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary fw-bold shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-lg me-2"></i>Nova Matéria
        </a>
    </div>
    
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-muted small fw-bold text-uppercase border-bottom-0">Matéria</th>
                            <th class="py-3 px-4 text-muted small fw-bold text-uppercase border-bottom-0">Status</th>
                            <th class="py-3 px-4 text-muted small fw-bold text-uppercase border-bottom-0">SEO Tracker</th>
                            <th class="py-3 px-4 text-muted small fw-bold text-uppercase border-bottom-0">Assinatura</th>
                            <th class="py-3 px-4 text-muted small fw-bold text-uppercase border-bottom-0 text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newsList as $news)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold text-dark lh-sm mb-1">{{ \Illuminate\Support\Str::limit($news->title, 60) }}</div>
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $news->created_at->format('d/m/Y \à\s H:i') }}</small>
                                </td>
                                <td class="py-3 px-4">
                                    @if($news->state === \App\Enums\NewsStateEnum::PUBLISHED->value)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success fw-semibold"><i class="bi bi-check-lg me-1"></i>Publicado</span>
                                    @elseif($news->state === \App\Enums\NewsStateEnum::IN_REVIEW->value)
                                        <span class="badge bg-warning bg-opacity-25 text-dark border border-warning fw-semibold"><i class="bi bi-eye me-1"></i>Em Revisão</span>
                                    @else
                                        <span class="badge bg-secondary fw-semibold"><i class="bi bi-file-earmark me-1"></i>Rascunho</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="badge bg-light text-dark border border-secondary border-opacity-25" style="border-left: 3px solid var(--portal-secondary) !important;">{{ $news->category->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-4 text-secondary fw-semibold small">
                                    <i class="bi bi-person-fill me-1"></i>{{ $news->author->name }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm btn-light border text-primary" title="Editar / Revisar"><i class="bi bi-pencil-square"></i></a>
                                        <button class="btn btn-sm btn-light border text-danger" title="Mover p/ Lixeira"><i class="bi bi-trash3"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="bg-light d-inline-flex justify-content-center align-items-center rounded-circle mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-journal-x fs-1 opacity-50"></i>
                                    </div>
                                    <h5 class="fw-bold fs-6">Sua pauta está limpa</h5>
                                    <p class="small opacity-75 mb-0">Nenhuma matéria foi registrada no banco de dados com essas restrições.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end">
        {{ $newsList->links() }}
    </div>
</div>
