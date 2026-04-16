<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 bg-white p-4 p-md-5">
                <div class="text-center mb-5">
                    <h3 class="fw-bolder mb-2 text-dark" style="font-family: 'Outfit', sans-serif;">
                        <i class="bi bi-shield-lock-fill text-danger me-2"></i>Google Authenticator
                    </h3>
                    <p class="text-muted">Acesso restrito detectado. Digite o PIN Base32 do seu app Autenticador ou utilize um Código de Backup (Fallback).</p>
                </div>

                <form wire:submit.prevent="verify">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold small px-1 text-uppercase letter-spacing-1">Código de Segurança Central</label>
                        <input type="text" wire:model="code" class="form-control form-control-lg bg-light border-0 shadow-none py-3 fs-3 fw-bold text-center text-primary" style="letter-spacing: 12px;" placeholder="000000" maxlength="6" autofocus autocomplete="one-time-code">
                        @error('code') <span class="text-danger small fw-semibold mt-2 d-block"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm py-3 mb-3 border-0 transition-hover outline-none" style="border-radius: 8px;">
                        <span wire:loading.remove wire:target="verify">
                            <i class="bi bi-door-open-fill me-2"></i> Validar TOTP
                        </span>
                        <span wire:loading wire:target="verify">
                            <span class="spinner-border spinner-border-sm me-2 float-start mt-1"></span> Criptografando...
                        </span>
                    </button>
                </form>
            </div>
            <p class="text-center text-muted small">
                &copy; {{ date('Y') }} Portal de Notícias.<br>Ambiente criptografado e estrito (Laravel Sanctum).
            </p>
        </div>
    </div>
</div>
