<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4" style="background-color: white;">
                <div class="bg-primary text-white text-center py-5" style="border-bottom: 5px solid var(--portal-secondary);">
                    <h2 class="fw-bolder mb-0" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-globe-americas text-warning me-2"></i>PORTAL</h2>
                    <p class="mb-0 mt-2 text-white-50 small text-uppercase tracking-wider">Acesso Restrito</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form wire:submit.prevent="authenticate">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">E-mail de acesso</label>
                            <input type="email" wire:model="email" class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror" placeholder="nome@exemplo.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Senha</label>
                            <input type="password" wire:model="password" class="form-control form-control-lg bg-light border-0 @error('password') is-invalid @enderror" placeholder="••••••••">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check">
                                <input class="form-check-input shadow-none" type="checkbox" wire:model="remember" id="rememberMe">
                                <label class="form-check-label text-muted small" for="rememberMe">
                                    Mantenha-me conectado
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none small fw-bold text-primary">Esqueceu a senha?</a>
                        </div>

                        <div class="d-grid relative">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 text-white rounded-3 shadow-sm" wire:loading.attr="disabled" style="transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                                <span wire:loading.remove>Entrar no Sistema</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Autenticando...</span>
                            </button>
                        </div>

                        <div class="position-relative my-4">
                            <hr class="text-black-50">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small fw-semibold">OU</span>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('social.redirect', 'google') }}" class="btn btn-outline-dark btn-lg fw-bold py-3 text-dark rounded-3 d-flex align-items-center justify-content-center hover-shadow" style="transition: all 0.2s;">
                                <i class="bi bi-google text-danger me-2 fs-5"></i> Continuar com o Google
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <p class="text-center text-muted small">
                &copy; {{ date('Y') }} Portal de Notícias.<br>Ambiente criptografado e estrito (Laravel Sanctum).
            </p>
        </div>
    </div>
</div>
