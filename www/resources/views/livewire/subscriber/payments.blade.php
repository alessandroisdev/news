<div class="container py-5">
    <div class="row">
        @include('layouts.partials.subscriber-sidebar')

        <div class="col-lg-9 ms-lg-1">
            
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <i class="bi bi-credit-card-2-front fs-2 text-primary me-3"></i>
                <div>
                    <h3 class="fw-bolder mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">Histórico de Faturas</h3>
                    <p class="text-muted small mb-0">Relação completa de todos os acertos da sua mensalidade via Integração Pix Transparente.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <div class="table-responsive h-100">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">ID Transação Asaas</th>
                                    <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Emissão</th>
                                    <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Valor</th>
                                    <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td class="py-3 px-4 fw-bold text-dark font-monospace text-muted" style="font-size: 0.85rem;">
                                            {{ $payment->asaas_payment_id ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4 text-muted small">
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="py-3 px-4 fw-bold text-dark">
                                            R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($payment->status === \App\Enums\PaymentStatusEnum::PAID->value)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-check-circle-fill me-1"></i> Aprovado</span>
                                            @elseif($payment->status === \App\Enums\PaymentStatusEnum::PENDING->value)
                                                <span class="badge bg-warning bg-opacity-25 text-dark border border-warning"><i class="bi bi-clock-history me-1 text-warning"></i> Aguardando Pix</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="bi bi-x-circle-fill me-1"></i> Vencido/Expirado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-receipt ms-2 fs-1 d-block mb-3 opacity-50"></i>
                                            Nenhum histórico financeiro encontrado nesta conta.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $payments->links() }}
            </div>

        </div>
    </div>
</div>
