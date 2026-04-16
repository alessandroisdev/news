<div x-data="voicePlayer('{{ addslashes($content) }}')" class="bg-light border rounded-pill px-4 py-2 my-4 d-inline-flex align-items-center shadow-sm" style="max-width: 100%;">
    <div class="me-3">
        <button @click="togglePlay" class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; transition: all 0.2s;">
            <i class="bi text-white fs-5" :class="isPlaying ? 'bi-pause-fill' : 'bi-play-fill'"></i>
        </button>
    </div>
    <div class="d-flex flex-column justify-content-center">
        <span class="fw-bold text-dark mb-0 lh-1" style="font-size: 0.95rem;">Ouça esta notícia</span>
        <span class="text-muted small lh-1" x-text="statusText">Duração estimada: Carregando...</span>
    </div>
    
    <div class="ms-4 ps-4 border-start d-flex align-items-center" x-show="isPlaying || isPaused">
        <button @click="stop" class="btn btn-sm btn-light border text-danger me-2"><i class="bi bi-stop-fill"></i> Parar</button>
        <div class="dropdown">
            <button class="btn btn-sm btn-light border text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-speedometer2"></i> <span x-text="rate + 'x'">1.0x</span>
            </button>
            <ul class="dropdown-menu shadow-sm border-0">
                <li><a class="dropdown-item" href="#" @click.prevent="setRate(0.8)">0.8x Lento</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="setRate(1.0)">1.0x Normal</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="setRate(1.5)">1.5x Rápido</a></li>
                <li><a class="dropdown-item" href="#" @click.prevent="setRate(2.0)">2.0x Muito Rápido</a></li>
            </ul>
        </div>
    </div>

    <!-- Engine Script Localizada com Alpine.JS -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('voicePlayer', (textToRead) => ({
                text: textToRead,
                isPlaying: false,
                isPaused: false,
                rate: 1.0,
                utterance: null,
                statusText: '',
                
                init() {
                    // Calcula duração estimada baseada na vel humana (180 palavras/min)
                    const words = this.text.split(' ').length;
                    const minutes = Math.ceil(words / 180);
                    this.statusText = `Duração estimada: ${minutes} min`;
                    
                    if ('speechSynthesis' in window) {
                        this.utterance = new SpeechSynthesisUtterance(this.text);
                        this.utterance.lang = 'pt-BR';
                        
                        this.utterance.onend = () => {
                            this.isPlaying = false;
                            this.isPaused = false;
                        };
                    } else {
                        this.statusText = 'Navegador não suporta áudio.';
                    }
                },
                
                togglePlay() {
                    if (!this.utterance) return;
                    
                    if (this.isPlaying) {
                        window.speechSynthesis.pause();
                        this.isPlaying = false;
                        this.isPaused = true;
                    } else if (this.isPaused) {
                        window.speechSynthesis.resume();
                        this.isPlaying = true;
                        this.isPaused = false;
                    } else {
                        // Start fresh
                        window.speechSynthesis.cancel(); // Clears any stuck queues
                        this.utterance.rate = this.rate;
                        window.speechSynthesis.speak(this.utterance);
                        this.isPlaying = true;
                        this.isPaused = false;
                    }
                },
                
                stop() {
                    if (this.utterance) {
                        window.speechSynthesis.cancel();
                        this.isPlaying = false;
                        this.isPaused = false;
                    }
                },
                
                setRate(speed) {
                    this.rate = speed;
                    if(this.isPlaying) {
                         this.stop();
                         this.togglePlay();
                    }
                }
            }))
        })
    </script>
</div>
