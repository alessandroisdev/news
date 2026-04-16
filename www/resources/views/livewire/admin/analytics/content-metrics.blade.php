<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bolder"><i class="bi bi-graph-up-arrow me-2 text-primary"></i> Auditoria de Conteúdo</h1>
            <p class="text-muted mt-1">Mapa termal de eficiência (Cliques Únicos vs PageViews Totais)</p>
        </div>
    </div>

    <!-- Abas / Tabs de Navegação -->
    <ul class="nav nav-tabs mb-4 border-bottom-0">
        <li class="nav-item">
            <button class="nav-link fw-bold border-0 border-bottom {{ $tab === 'news' ? 'active border-primary text-primary' : 'text-muted' }}" 
                    style="{{ $tab === 'news' ? 'border-bottom-width: 3px !important;' : '' }}"
                    wire:click="setTab('news')">
                <i class="bi bi-newspaper me-1"></i> Notícias (Matérias)
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold border-0 border-bottom {{ $tab === 'columnists' ? 'active border-primary text-primary' : 'text-muted' }}" 
                    style="{{ $tab === 'columnists' ? 'border-bottom-width: 3px !important;' : '' }}"
                    wire:click="setTab('columnists')">
                <i class="bi bi-pen me-1"></i> Colunistas
            </button>
        </li>
    </ul>

    <!-- Area de Filtro -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input wire:model.live.debounce.300ms="search" type="text" class="form-control border-start-0" placeholder="Buscar por {{ $tab === 'news' ? 'título da matéria' : 'nome do colunista' }}...">
            </div>
        </div>
    </div>

    <!-- Renderização das Tabelas -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Objeto Editorial</th>
                            <th class="text-center">Total de Views (bruto)</th>
                            <th class="text-center">Usuários Únicos</th>
                            <th>Termômetro de Hype (Selo)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($tab === 'news')
                            @forelse($news as $item)
                                @php
                                    $daysOld = $item->published_at ? $item->published_at->diffInDays(now()) : 0;
                                    $isWeak = $item->total_views < 10 && $daysOld > 2; // Fraco se tem mais de 2 dias e < 10 views
                                    $isStrong = $item->unique_views > 50; // Forte se bater 50 uniques rápidos
                                @endphp
                                <tr class="{{ $isWeak ? 'table-danger table-opacity-10' : '' }}">
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ \Illuminate\Support\Str::limit($item->title, 60) }}</div>
                                        <div class="text-muted small">
                                            Publicado há {{ $item->published_at ? $item->published_at->diffForHumans() : 'N/A' }} 
                                            &bull; Categoria: {{ optional($item->category)->name }}
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold text-secondary">{{ number_format($item->total_views) }}</td>
                                    <td class="text-center fw-bold text-primary">{{ number_format($item->unique_views) }}</td>
                                    <td>
                                        @if($isStrong)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-fire"></i> Viralizando</span>
                                        @elseif($isWeak)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="bi bi-exclamation-triangle"></i> Baixa Retenção</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">Tração Média</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Ainda não há dados capturados.</td></tr>
                            @endforelse
                        @else
                            @forelse($columnists as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $item->name }}</div>
                                        <div class="text-muted small">{{ $item->news()->count() }} artigos postados</div>
                                    </td>
                                    <td class="text-center fw-bold text-secondary">{{ number_format($item->total_views) }}</td>
                                    <td class="text-center fw-bold text-primary">{{ number_format($item->unique_views) }}</td>
                                    <td>
                                        @if($item->unique_views > 100)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Nível Prata</span>
                                        @elseif($item->unique_views > 500)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> Nível Ouro</span>
                                        @else
                                            <span class="badge bg-light text-dark border">Iniciante</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Ainda não há dados capturados.</td></tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-top">
                @if($tab === 'news')
                    {{ $news->links() }}
                @else
                    {{ $columnists->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
