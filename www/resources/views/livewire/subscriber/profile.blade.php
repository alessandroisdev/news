<div class="container py-5">
    <div class="row">
        @include('layouts.partials.subscriber-sidebar')

        <div class="col-lg-9 ms-lg-1">
            
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <i class="bi bi-person-badge fs-2 text-primary me-3"></i>
                <div>
                    <h3 class="fw-bolder mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">Minha Identidade</h3>
                    <p class="text-muted small mb-0">Atualize seus dados pessoais e verifique as credenciais de acesso seguro.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 p-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-card-text text-primary me-2"></i>Informações Básicas</h6>
                </div>
                <div class="card-body p-4">
                    @if (session()->has('profile-updated'))
                        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3 p-3 mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('profile-updated') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase small">Nome Completo</label>
                                <input wire:model="name" type="text" class="form-control bg-light border-0 shadow-none px-3 py-2" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase small">Correio Eletrônico (Login)</label>
                                <input wire:model="email" type="email" class="form-control bg-light border-0 shadow-none px-3 py-2" required>
                                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4">
                                    <span wire:loading.remove wire:target="updateProfile">Salvar Alterações</span>
                                    <span wire:loading wire:target="updateProfile"><span class="spinner-border spinner-border-sm me-2"></span>Salvando...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 p-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock text-primary me-2"></i>Revisão de Segurança Limitada (Hash)</h6>
                </div>
                <div class="card-body p-4">
                    @if (session()->has('password-updated'))
                        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3 p-3 mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('password-updated') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary text-uppercase small">Senha Atual</label>
                            <input wire:model="current_password" type="password" class="form-control bg-light border-0 shadow-none px-3 py-2" style="max-width: 400px;" placeholder="Informe sua senha atual">
                            @error('current_password') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-3 mb-4" style="max-width: 800px;">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase small">Nova Senha</label>
                                <input wire:model="new_password" type="password" class="form-control bg-light border-0 shadow-none px-3 py-2" placeholder="Digite uma nova e forte">
                                @error('new_password') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase small">Confirmar Nova Senha</label>
                                <input wire:model="new_password_confirmation" type="password" class="form-control bg-light border-0 shadow-none px-3 py-2" placeholder="Repita a senha escrita">
                            </div>
                        </div>

                        <div class="text-start">
                            <button type="submit" class="btn btn-dark fw-bold shadow-sm px-4">
                                <span wire:loading.remove wire:target="updatePassword"><i class="bi bi-lock-fill me-2"></i> Atualizar Chave de Ouro</span>
                                <span wire:loading wire:target="updatePassword"><span class="spinner-border spinner-border-sm me-2"></span>Criptografando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
