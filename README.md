<p align="center"><a href="#" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 📰 Portal News (Premium Mode)

Este é um Sistema Completo de Gestão de Conteúdo (CMS) e Jornalismo construído com o framework corporativo **Laravel 11+** e turbinado pela interatividade em Server-Side Rendering do **Livewire 3**. 

Focado em escalabilidade, o projeto vai muito além de um blog comum, entregando ferramentas Enterprise para gestão de colunistas, tracking orgânico multicanal e moderação comportamental de comunidade baseada em arquiteturas de tráfego de grandes plataformas.

---

## 🚀 Camadas Arquiteturais e Features Base

### 1. Motor de Notícias e Taxonomia (SEO-First)
- **CMS Headless-Ready:** Criação e Edição unificada com suporte a WYSIWYG.
- **Categorização SEO:** Injeção profunda de Meta-tags por componente permitindo customização cromática/cores onde cada coluna e categoria pode ter sua própria paleta de marca baseada em SASS dinâmico.
- **Feeds Globais RSS / XML:** Despacho nativo indexável com by-pass seguro do cachê (Linter) garantindo interoperabilidade com Google News e Indexadores XML automáticos (`/sitemap.xml` e `/feed.rss`).

### 2. Livewire AdTech (Banners & Streaming Audiência)
- Motor robusto focado em Ad-Servers e conversão (Banner dinâmico nativo em grid).
- **In-Memory Streaming (Server-Sent Events)**: Graças ao proxy otimizado (preparado pro Octane/FrankenPHP), atualizações de conteúdo ou tabelas na área administrativa batem no front instantaneamente sem o cliente derrubar as conexões alheias consumindo requisições curtas de Ajax.

### 3. Autenticação e Perfis (Subscriber Space)
- **Dashboard do Leitor Logado:** Os leitores/assinantes VIP tem acesso focado a área de Autogestão (`/assinante`).
- **Paywall Automático e Faturas:** Módulo acoplado de cobrança e histórico via integração com **API Asaas (PIX Transparente)**. O sistema fecha as rotas Premium com Intercepts robustos.
- Upload assíncrono avançado de Avatares Sociais na nuvem sem reloading de página.

### 4. Engine PWA (Offline-First)
- O portal não é apenas um site, é um **Progressive Web App** instalável na área de trabalho e mobile.
- Possui injeção de **Service Workers** interligados a rotas de *Fallback Offline* (`/offline`), garantindo utilidade e retenção do usuário caso a internet caia num engarrafamento ou zona cega, carregando UI otimizada de falha segura.

---

## 🛡️ As "Killer Features" Desenvolvidas

O projeto conta com módulos especializados desenvolvidos cirurgicamente para as dores do Jornalismo diário e Trolls de Internet.

### 📊 Sistema Data-Driven (Auditor de Conteúdo)
Abandona as engines pesadas e baseadas em JavaScript cliente (Como o TagManager) para focar numa arquitetura **Polimórfica Server-side**.
- **PageViews Brutos vs Cliques Únicos:** O `FallbackRouteController` assinala visitas pontuais baseados na impressão digital/Cookies do _VisitorProfile_ diretamente no Banco. Um cara atualizando com F5 nunca mais vai quebrar a métrica e corromper sua tabela de "Matérias Mais Vistas".
- **Hype Thermometer:** Dashboard tático que qualifica a Notícia/Coluna como **Viralizando** (Engajamento Extremo) ou **Baixa Retenção** (Flopou). É a bússola para os Copywriters do Portal.

### ⚙️ Injeção Dinâmica Global (Settings Service Provider)
Totalmente imune a quebras do arquivo `.env`. As configurações chaves da plataforma agora ficam no seu Banco de Dados!
- Os donos podem gerenciar e repor chaves (Ex: Mudou de Amazon SES para SendGrid? Só trocar no painel e o Laravel entende). O `SettingsServiceProvider` anula as pré-configurações no boot da aplicação reescrevendo-as em Memória Flash.

### 🕵️ Autodefesa e Sistema Social (O Poder do Shadowban)
Um fórum blindado estilo Reddit integrado ao corpo da sua narrativa.
- **Árvores Recursivas:** Threads hierárquicas que suportam conversas em nível (Pai responde Filho).
- **O Banimento Fantasma (Shadowban):** Chegou um troll? Assim que ofender usando um léxico presente em sua Blacklist, a ofensa entrará tranquilamente na tela dele. Gênial né? Contudo, o BD bloqueia para quem não é ele a visualização (Isola em quarentena de silêncio e desarma o atacante).
- **Strikes System:** Acionou o Shadowban 3 vezes? O motor detona as interações pra sempre.

### 🔐 Segurança Militar Zero-Trust (TOTP 2FA)
A base administrativa foi desenvolvida pensando em brechas humanas de nível global.
- **Algoritmo Base32 Universal:** Se o Administrador optar pela blindagem e ligar a chave 2FA, a Engine aciona uma matemática local via pacote `Google2Fa` que travará o CMS e exigirá leitura via QR-Code num App Autenticador. 
- **Recovery Codes (Engine PDF):** Perdeu o celular? Abaixo do QR Code, renderiza nativamente na tela **8 Códigos Random Alfanuméricos Exclusivos** salvos como array estrito no MariaDB/MySQL.
- **Integração PDF e E-mail:** Com a biblioteca `dompdf`, a administração baixa esse lote de 8 senhas "Mata-burro" numa Sulfite virtual (`.pdf`) na sua própria máquina e ou recebe instantaneamente por smtp. Se um destes tentar abrir a porta da plataforma, a senha explode (Self-destruct) antes do gestor entrar e o cofre limpa o rastro.

### 💰 Funil de Retenção (Metered Paywall & OAuth)
Engrenagens de tráfego orgânico nativo que substituem dezenas de bibliotecas analíticas e gatilhos lentos externos.
- **Metered Paywall (Catraca Flexível):** Um cruzamento da tabela polimórfica `AudienceMetric` permite contar estritamente as matérias Premium lidas pelo usuário único mensalmente. O bloqueio desfoca a matéria ao atingir a 4ª inserção de forma hard-stop, sem depender de cache frágil de JS.
- **Single Sign-On (Social Login):** Derrubada de atrito! Usando o driver robusto nativo OAuth, o Portal integra de forma híbrida com a Google sem criar chaves corrompidas e rouba a exata tipografia da foto de perfil direto pro disco rígido local do servidor eliminando formulários absurdos.

### 📣 Aquisição Furtiva (Web Push e OpenGraph)
- **Web Push Engine (VAPID Nativo):** Esqueça o OneSignal. Usando o protocolo Web Push encrustrado no nosso `sw.js` (Worker PWA), a redação aperta a chave vermelha de *Breaking News* no painel e milhares de telas vão pulsar a matéria nos celulares sem necessidade de intermédio corporativo externo e sem taxas em dólar.
- **A Arte do SEO (Intervention Image):** Você escolhe a foto e escreve um super título. Nosso pacote de `Observer/Boot` interceptará a criação da notícia para pintar um degradê, aplicar sua Logo em marca d'água e quebrar o Texto perfeitamente numa imagem invisível de JPG 1200x630. Essa tela não-existente é lida secretamente pelas meta-tags estáticas para reter e espalhar pelo WhatsApp os seus cards muito lindamente polidos.

---

## 🛠️ Stack Tecnológica

*   [PHP 8.4+](https://php.net) - A fundação da máquina
*   [Laravel 11.x](https://laravel.com) - Framework Arquitetural MVC
*   [Livewire 3.x](https://livewire.laravel.com/) - Server-Side Reactive UI
*   [Bootstrap 5.3](https://getbootstrap.com) / Vanilla SCSS - Componentes e Framework Grid Visual
*   [Docker Sail](https://laravel.com/docs/sail) - Containerização do ambiente local
*   Banco de Dados Relacional e Arquitetura SoftDelete para Histórico

> Um projeto preparado para impacto em massa, retenção de público, e tráfego jornalístico seguro.
