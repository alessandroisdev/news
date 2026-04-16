<?= '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
    <title><![CDATA[Portal Definitivo - Feed de Notícias RSS]]></title>
    <link>{{ url('/') }}</link>
    <description><![CDATA[O seu portal moderno focado em alta velocidade, recomendações de machine learning leve, e extrema aderência com leitura mobile-first.]]></description>
    <language>pt-br</language>
    <pubDate>{{ now()->toRfc2822String() }}</pubDate>
    <generator>Sistema Editorial Híbrido (Laravel)</generator>
    <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml"/>
    
    @foreach($news as $n)
    <item>
        <title><![CDATA[{{ $n->title }}]]></title>
        <link>{{ route('dynamic.slug', $n->slug) }}</link>
        <guid isPermaLink="true">{{ route('dynamic.slug', $n->slug) }}</guid>
        
        <description><![CDATA[
            @if($n->cover_image)
               <img src="{{ asset('storage/' . $n->cover_image) }}" alt="{{ $n->title }}" /><br /><br />
            @endif
            {{ $n->excerpt ?? Str::limit(strip_tags($n->content), 200, '...') }}
        ]]></description>
        
        @if($n->cover_image)
        <media:content url="{{ asset('storage/' . $n->cover_image) }}" medium="image" />
        @endif
        
        <category><![CDATA[{{ optional($n->category)->name ?? 'Geral' }}]]></category>
        <pubDate>{{ \Carbon\Carbon::parse($n->published_at ?? $n->created_at)->toRfc2822String() }}</pubDate>
        <dc:creator><![CDATA[{{ optional($n->author)->name ?? 'Redação' }}]]></dc:creator>
    </item>
    @endforeach
</channel>
</rss>
