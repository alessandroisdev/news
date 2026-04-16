<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bolder"><i class="bi bi-gear-fill me-2 text-primary"></i> Configurações Globais</h1>
            <p class="text-muted mt-1">Gerencie chaves do sistema de forma segura sem tocar no servidor</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="list-group shadow-sm border-0 rounded-3 mb-4">
                <button wire:click="$set('activeTab', 'general')" class="list-group-item list-group-item-action border-0 p-3 {{ $activeTab === 'general' ? 'active rounded-top-3 fw-bold' : 'text-secondary' }}">
                    <i class="bi bi-building me-2"></i> Dados da Redação
                </button>
                <button wire:click="$set('activeTab', 'smtp')" class="list-group-item list-group-item-action border-0 p-3 {{ $activeTab === 'smtp' ? 'active rounded-bottom-3 fw-bold' : 'text-secondary' }}">
                    <i class="bi bi-envelope-at me-2"></i> Motor de Email (SMTP)
                </button>
            </div>
        </div>

        <div class="col-md-9">
            @if($activeTab === 'general')
            <!-- Dados da Redação -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark"><i class="bi bi-info-square me-2 text-muted"></i> Informações do Portal</h5>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="saveGeneral">
                        <div class="row g-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-muted small fw-bold">Nome do Site / Portal</label>
                                <input type="text" class="form-control" wire:model="site_name" placeholder="Ex: Portal Norte">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-muted small fw-bold">Telefone (Contato Central)</label>
                                <input type="text" class="form-control" wire:model="site_phone" placeholder="+55 (00) 00000-0000">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label text-muted small fw-bold">Endereço Público da Redação</label>
                                <input type="text" class="form-control" wire:model="site_address" placeholder="Av. Principal, 1000 - Centro">
                            </div>
                            
                            <hr class="my-4 text-muted">
                            <h6 class="fw-bold mb-3">Redes Sociais Oficiais</h6>
                            
                            <div class="col-md-4 mb-2">
                                <label class="form-label text-muted small fw-bold"><i class="bi bi-facebook text-primary"></i> Facebook (URL)</label>
                                <input type="text" class="form-control" wire:model="site_facebook">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label text-muted small fw-bold"><i class="bi bi-instagram text-danger"></i> Instagram (URL)</label>
                                <input type="text" class="form-control" wire:model="site_instagram">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label text-muted small fw-bold"><i class="bi bi-twitter-x text-dark"></i> Twitter/X (URL)</label>
                                <input type="text" class="form-control" wire:model="site_twitter">
                            </div>
                            
                            <hr class="my-4 text-muted">
                            <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-shield-lock-fill me-1"></i> Moderação & Comunidade</h6>
                            
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" role="switch" id="allow_comments_globally" wire:model="allow_comments_globally">
                                    <label class="form-check-label fs-6 fw-bold" for="allow_comments_globally">Habilitar Motor de Comentários no Portal Inteiro</label>
                                </div>
                                <small class="text-muted d-block mt-1">Se desligado, desativa o formulário de comentários em todas as notícias independentemente de serem permitidos individualmente.</small>
                            </div>
                            
                            <div class="col-md-12 mb-2">
                                <label class="form-label text-muted small fw-bold">Lista Negra do Shadowban (Termos Censurados)</label>
                                <p class="small text-muted mb-2">Qualquer menção a essas palavras nos comentários colocará o autor no <span class="badge bg-dark bg-opacity-10 text-dark border">Shadowban</span> (ninguém vê o comentário além do agressor). Separe por vírgulas.</p>
                                <textarea class="form-control border-danger border-opacity-50" wire:model="banned_words" rows="3" placeholder="merda, foder, estúpido, etc..."></textarea>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                <span wire:loading.remove wire:target="saveGeneral">Salvar Informações</span>
                                <span wire:loading wire:target="saveGeneral">Gravando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($activeTab === 'smtp')
            <!-- Motor SMTP -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark"><i class="bi bi-server me-2 text-muted"></i> Servidor de E-mail de Saída</h5>
                    <p class="text-muted small">Isto fará override do arquivo config/mail.php permitindo usar SendGrid, Amazon SES, etc sem tocar no servidor.</p>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="saveSmtp">
                        <div class="row g-3 bg-light p-3 rounded-3 mb-4 border">
                            <div class="col-md-8 mb-2">
                                <label class="form-label text-muted small fw-bold">SMTP Host</label>
                                <input type="text" class="form-control" wire:model="smtp_host" placeholder="smtp.mailtrap.io">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label text-muted small fw-bold">Porta</label>
                                <input type="text" class="form-control" wire:model="smtp_port" placeholder="587">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label text-muted small fw-bold">Segurança</label>
                                <select class="form-select" wire:model="smtp_encryption">
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                    <option value="">Nenhuma</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-muted small fw-bold">Usuário (Username)</label>
                                <input type="text" class="form-control" wire:model="smtp_username">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-muted small fw-bold">Senha (Password)</label>
                                <input type="password" class="form-control" wire:model="smtp_password">
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3">Identidades e Destinatários</h6>
                        <div class="row g-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-muted small fw-bold">E-mail Remetente Padrão</label>
                                <input type="email" class="form-control" wire:model="mail_from_address" placeholder="no-reply@seusite.com">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-muted small fw-bold">Nome de Remetente Padrão</label>
                                <input type="text" class="form-control" wire:model="mail_from_name" placeholder="Portal Premium">
                            </div>
                            <div class="col-md-12 mt-3 pt-3 border-top">
                                <label class="form-label text-dark fw-bold"><i class="bi bi-box-arrow-in-down me-1"></i> E-mail Receptor de Contatos da Redação</label>
                                <p class="small text-muted mb-2">Esta é a caixa externa onde os Leitores enviarão denúncias usando a página do site (Ex: contato@seusite.com).</p>
                                <input type="email" class="form-control border-primary" wire:model="contact_receiver_email" placeholder="redacao@portal.com">
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                <span wire:loading.remove wire:target="saveSmtp">Efetivar no Core do Sistema</span>
                                <span wire:loading wire:target="saveSmtp">Reiniciando Caches...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
