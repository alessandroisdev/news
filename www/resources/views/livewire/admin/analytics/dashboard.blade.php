<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Central Intelligence & Analytics</h3>
            <p class="text-muted small mb-0">Rastreamento de Tráfego, Perfil Transacional e Preferências Geográficas.</p>
        </div>
    </div>

    <!-- KPIs -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-fingerprint fs-1"></i>
                    </div>
                    <div>
                        <h6 class="text-white text-opacity-75 fw-bold mb-1 text-uppercase letter-spacing-1">Reach Global (Histórico)</h6>
                        <h2 class="fw-bolder display-5 mb-0" style="font-family: 'Outfit'">{{ number_format($totalVisitors, 0, ',', '.') }}</h2>
                        <small class="text-white text-opacity-75">Fingerprints anônimos mapeados</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-activity fs-1"></i>
                    </div>
                    <div>
                        <h6 class="text-white text-opacity-75 fw-bold mb-1 text-uppercase letter-spacing-1">Navegação Recente (24h)</h6>
                        <h2 class="fw-bolder display-5 mb-0" style="font-family: 'Outfit'">{{ number_format($activeLast24h, 0, ',', '.') }}</h2>
                        <small class="text-white text-opacity-75">Sessões ativas no último dia circular</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Mapa Cidades -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Concentração Geográfica (GEO-IP Tracker)</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($locations as $cidade => $qtd)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-light py-3">
                                <span class="fw-semibold text-secondary">{{ $cidade }}</span>
                                <span class="badge bg-light text-dark shadow-sm border px-3 py-2 fs-6 rounded-pill">
                                    {{ $qtd }} <i class="bi bi-person ms-1"></i>
                                </span>
                            </li>
                        @empty
                            <p class="text-muted text-center py-4">Sistema populando os dados via rede Neural (Aguarde captações em produção).</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Devices -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-phone text-primary me-2"></i>Dispositivos em Uso</h6>
                    @foreach($devices as $dev => $qtd)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold text-muted">{{ $dev ?: 'Desconhecido' }}</span>
                            <span class="fw-bold fs-5">{{ $qtd }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ $totalVisitors > 0 ? ($qtd/$totalVisitors)*100 : 0 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4">
                 <div class="card-body">
                    <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-browser-chrome text-warning me-2"></i>Top Browsers</h6>
                    @foreach($browsers as $br => $qtd)
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                            <span class="small fw-semibold text-muted">{{ $br ?: 'Desconhecido' }}</span>
                            <span class="badge bg-light text-dark fw-bold border">{{ $qtd }}</span>
                        </div>
                    @endforeach
                 </div>
            </div>
        </div>
    </div>

    <!-- Hype Train (Scores) -->
    <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden position-relative">
        <div class="position-absolute top-0 end-0 mt-n4 me-n4 opacity-10">
            <i class="bi bi-fire" style="font-size: 15rem;"></i>
        </div>
        <div class="card-body p-5 position-relative z-1">
            <h4 class="fw-bolder text-warning mb-1" style="font-family: 'Outfit'"><i class="bi bi-lightning-charge-fill me-2"></i>Motor Semântico (Global Score)</h4>
            <p class="text-white text-opacity-75 mb-4">Veja quais editoriais do portal estão com mais "Hype" baseado no clique de perfis anônimos e leitores orgânicos.</p>
            
            <div class="row g-4">
                @php $i = 1; @endphp
                @forelse($topInterests as $name => $score)
                    <div class="col-md-4 col-sm-6">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-3 d-flex align-items-center">
                            <div class="display-6 fw-bold text-white text-opacity-25 me-3">#{{$i}}</div>
                            <div>
                                <h5 class="fw-bold mb-0 text-white">{{ $name }}</h5>
                                <small class="text-warning fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i> {{ $score }} Pontos</small>
                            </div>
                        </div>
                    </div>
                @php $i++; @endphp
                @empty
                    <p class="text-white opacity-50">Score model em calibração (0 visitas registradas).</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
