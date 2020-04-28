<?php namespace Http\Middleware;

use Closure;
use Carbon\Carbon;
use Cache;
use Agent;
use Models\Sitemap;

class VnSourceBefore {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //View share user
        view()->share('user', $request->user());

        view()->share('agent', Agent::get());

        if (config('site.maintenance')
            && (empty($request->user()) || !$request->user()->can('dashboard'))
            && !$request->is(config('site.admin_path').'*')
            && !$request->is('avatar/*')
            && !$request->is('test*')
            && !$request->is('api/*')
            && !$request->is('my/*')
            && !$request->is('*/login')
            && !$request->is('*/authorize')
            && !$request->is('login')
            && !$request->is('forgot-password')
            && !$request->is('reset-password*')
            && !$request->is('logout')
            && !$request->is('sitemap*xml'))
        {
          return abort('503');
        }

        //Build Sitemap
        if ( $request->isMethod('get')
            && !$request->ajax()
            && !$request->has('PageSpeed')
            && !$request->is(config('site.admin_path').'*')
            && !$request->is('avatar/*')
            && !$request->is('test*')
            && !$request->is('api/*')
            && !$request->is('my/*')
            && !$request->is('*/login')
            && !$request->is('*/authorize')
            && !$request->is('login')
            && !$request->is('forgot-password')
            && !$request->is('reset-password*')
            && !$request->is('logout')
            && !$request->is('sitemap*xml'))
        {
            if (config('site.big_site')) {
              Sitemap::updateOrCreate(['uri'=>($request->getPathInfo()?:'/')], ['updated_at'=>Carbon::now()]);
            } else {
              $aSiteMap = Cache::get('sitemap', []);
              $aSiteMap[url($request->getPathInfo())] = Carbon::now()->format('Y-m-d\TH:i:sP');
              Cache::forever('sitemap', $aSiteMap);
            }
        }

        return $next($request);
    }
}
