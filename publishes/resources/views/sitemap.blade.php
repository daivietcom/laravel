<?='<?xml version="1.0" encoding="UTF-8"?>'?>
@if (!empty($urls))
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  @foreach( $urls as $url => $lastmod )
    <url>
        <loc>{{$url}}</loc>
        <lastmod>{{$lastmod}}</lastmod>
    </url>
  @endforeach
</urlset>
@elseif(!empty($sitemaps))
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  @foreach ($sitemaps as $url => $lastmod)
   <sitemap>
      <loc>{{$url}}</loc>
      <lastmod>{{$lastmod}}</lastmod>
   </sitemap>
  @endforeach
</sitemapindex>
@endif
