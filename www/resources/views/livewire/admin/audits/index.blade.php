<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-shield-check text-primary me-2"></i>Auditoria de Acessos</h3>
            <p class="text-muted small mb-0">Rastreio contínuo e minucioso de cada mutação efetuada nos dados do portal.</p>
        </div>
        <button wire:click="exportCsv" class="btn btn-dark fw-bold shadow-sm d-flex align-items-center">
            <span wire:loading.remove wire:target="exportCsv"><i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> Exportar (CSV)</span>
            <span wire:loading wire:target="exportCsv"><span class="spinner-border spinner-border-sm me-2"></span>Gerando Relatório...</span>
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-bottom-0 p-4 d-flex gap-3">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                <input wire:model.live.debounce.300ms="searchUser" type="text" class="form-control bg-light border-0 shadow-none" placeholder="Investigar por Assinante/Colunista">
            </div>
            <select wire:model.live="actionFilter" class="form-select bg-light border-0 shadow-none w-auto">
                <option value="">Todas as Ações</option>
                <option value="CREATED">Apenas Criações (Inserts)</option>
                <option value="UPDATED">Apenas Modificações (Updates)</option>
                <option value="DELETED">Apenas Destruições (Deletes/Drops)</option>
            </select>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 border-bottom-0 text-secondary text-uppercase fw-bold" style="width:18%;">Marcador de Tempo</th>
                            <th class="py-3 px-4 border-bottom-0 text-secondary text-uppercase fw-bold">Responsável</th>
                            <th class="py-3 px-4 border-bottom-0 text-secondary text-uppercase fw-bold">Ação</th>
                            <th class="py-3 px-4 border-bottom-0 text-secondary text-uppercase fw-bold">Alvo do Evento</th>
                            <th class="py-3 px-4 border-bottom-0 text-secondary text-uppercase fw-bold text-end">Endereço de Origem (IP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="py-3 px-4 text-muted font-monospace small">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3 px-4 fw-semibold text-dark">
                                    {{ $log->user->name ?? 'Usuário Desconhecido ou Extinto' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($log->action === 'CREATED')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">CRIAÇÃO</span>
                                    @elseif($log->action === 'UPDATED')
                                        <span class="badge bg-warning bg-opacity-25 text-dark border border-warning">ALTERAÇÃO</span>
                                    @elseif($log->action === 'DELETED')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">EXCLUSÃO</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="fw-bold">{{ class_basename($log->auditable_type) }}</span> 
                                    <span class="text-muted">#{{ $log->auditable_id }}</span>
                                </td>
                                <td class="py-3 px-4 text-end text-muted font-monospace small">
                                    {{ $log->ip_address }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">A tabela de auditoria está perfeitamente selada sem registros recentes que combinem com a busca.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
