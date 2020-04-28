<?php namespace Http\Controllers;

use Cache;
use Carbon\Carbon;
use Models\Sitemap;

class SitemapController extends \App\Http\Controllers\Controller
{
    public function index($page = null) {
        $datas = [
            'urls' => [],
            'sitemaps' => []
        ];

        if (config('site.big_site')) {
            $per_sitemap = config('site.per_sitemap');
            $domain = config('app.url');
            if ($page === null) {
                $count = Sitemap::count();
                $total = ($count - ($count%$per_sitemap)) / $per_sitemap + 1;
                $dt = Carbon::now();
                $dt->day = 1;
                $dt->hour = 0;
                $dt->minute = 0;
                $dt->second = 0;
                for ($i=0; $i < $total; $i++) {
                    $datas['sitemaps'][rtrim($domain, '/').'/sitemap'.$i.'.xml'] = $dt->format('Y-m-d\TH:i:sP');
                }
            } else {
                $urls = Sitemap::skip($page*$per_sitemap)
                    ->limit($per_sitemap)
                    ->get()
                ;
                foreach ($urls as $url) {
                    $datas['urls'][url($url->uri)] = $url->updated_at->format('Y-m-d\TH:i:sP');
                }
            }
        } else {
            $datas['urls'] = Cache::get('sitemap', []);
        }
        return response()->view('sitemap', $datas)->header('Content-Type', 'application/xml');
    }
}