<div>
    <div class="text-center mb-5">
        <h3 class="fw-bolder mb-2 text-dark" style="font-family: 'Outfit', sans-serif;">
            <i class="bi bi-shield-lock-fill text-danger me-2"></i>Workspace Trancado
        </h3>
        <p class="text-muted">Acesso restrito detectado. Digite o código de 6 dígitos enviado para seu E-mail corporativo.</p>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 border-0 border-start border-4 border-success text-success fw-bold rounded-3 fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form wire:submit.prevent="verify">
        <div class="mb-4">
            <label class="form-label text-secondary fw-semibold small px-1 text-uppercase letter-spacing-1">Código de Segurança Central</label>
            <input type="text" wire:model="code" class="form-control form-control-lg bg-light border-0 shadow-none py-3 fs-3 fw-bold text-center text-primary" style="letter-spacing: 12px;" placeholder="000000" maxlength="6" autofocus autocomplete="one-time-code">
            @error('code') <span class="text-danger small fw-semibold mt-2 d-block"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm py-3 mb-3 border-0 transition-hover outline-none" style="border-radius: 8px;">
            <span wire:loading.remove wire:target="verify">
                <i class="bi bi-door-open-fill me-2"></i> Liberar Acesso Administrativo
            </span>
            <span wire:loading wire:target="verify">
                <span class="spinner-border spinner-border-sm me-2 float-start mt-1"></span> Criptografando...
            </span>
        </button>
        
        <div class="text-center mt-4">
            <a href="#" wire:click.prevent="resend" class="text-decoration-none text-muted fw-semibold small hover-text-primary transition-colors">
                <i class="bi bi-arrow-clockwise me-1"></i> Não recebeu ou Inspirou? Reenviar PIN.
            </a>
        </div>
    </form>
</div>
