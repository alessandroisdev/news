<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- Rotas Estáticas de Navegação (Maior Prioridade) -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/ultimas') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/colunistas') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Trilhas Taxonômicas (Categorias) -->
    @foreach ($categories as $category)
    <url>
        <loc>{{ route('dynamic.slug', $category->slug) }}</loc>
        <lastmod>{{ optional($category->updated_at)->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Indexações de Perfis Editoriais (Colunistas) -->
    @foreach ($columnists as $columnist)
    <url>
        <loc>{{ route('dynamic.slug', $columnist->slug) }}</loc>
        <lastmod>{{ optional($columnist->updated_at)->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        <!-- A imagem de avatar do colunista se tivermos poderia ir aqui com schema de image -->
    </url>
    @endforeach

    <!-- Corpo das Matérias (Notícias) -->
    @foreach ($news as $n)
    <url>
        <loc>{{ route('dynamic.slug', $n->slug) }}</loc>
        <lastmod>{{ optional($n->updated_at)->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        @if($n->cover_image)
        <image:image>
            <image:loc>{{ asset('storage/' . $n->cover_image) }}</image:loc>
            <image:title><![CDATA[{{ $n->title }}]]></image:title>
            @if($n->excerpt)
            <image:caption><![CDATA[{{ $n->excerpt }}]]></image:caption>
            @endif
        </image:image>
        @endif
    </url>
    @endforeach

</urlset>
