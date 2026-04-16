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
- Módulo de Faturas/Pagamentos acoplados (gateway pronto para Asaas).
- Upload assíncrono avançado de Avatares Sociais na nuvem sem reloading de página.

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

### 🕵️ Autodefesa e Sistema Social de Comentários (O Poder do Shadowban)
Um fórum blindado estilo Reddit/Facebook integrado ao corpo da sua narrativa.
- **Comentários em Árvores Recursivas:** Threads hierárquicas que suportam conversas em nível (Pai responde Filho).
- **O Banimento Fantasma (Shadowban):** Chegou um troll? Assim que ele ofender usando um léxico presente em sua Blacklist Dinâmica, o comentário dele aparentemente entrará tranquilamente na tela do autor. Gênial né? Contudo, o Banco de dados bloqueia para quem não é ele a visualização (Isola em quarentena de silêncio e desarma o atacante furioso).
- **A Regra das 3 Vidas (Strikes System):** Se ele acionar o Shadowban três vezes, o motor "desliga" o input de submissão do leitor para todo o sempre em todas as Notícias do portal.

---

## 🛠️ Stack Tecnológica

*   [PHP 8.4+](https://php.net) - A fundação da máquina
*   [Laravel 11.x](https://laravel.com) - Framework Arquitetural MVC
*   [Livewire 3.x](https://livewire.laravel.com/) - Server-Side Reactive UI
*   [Bootstrap 5.3](https://getbootstrap.com) / Vanilla SCSS - Componentes e Framework Grid Visual
*   [Docker Sail](https://laravel.com/docs/sail) - Containerização do ambiente local
*   Banco de Dados Relacional e Arquitetura SoftDelete para Histórico

> Um projeto preparado para impacto em massa, retenção de público, e tráfego jornalístico seguro.
