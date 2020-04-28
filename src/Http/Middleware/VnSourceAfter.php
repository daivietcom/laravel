<?php

namespace Http\Middleware;

use Closure;

class VnSourceAfter {

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
        $response = $next($request);
        if(!config('app.debug')) {
            $resClass = get_class($response);
            if ( $resClass == 'Illuminate\Http\Response'
            && !$request->is('avatar/*')
            && !$request->is('test*')
            && !$request->ajax()
            && !$request->wantsJson()) {

                $output = $response->content();
                // Clean
                $output = minify_html($output);

                $response->setContent($output);
            }
        }
        if(config('site.ssl')) {
            $output = $response->content();
            $output = str_replace('http://'.$_SERVER['SERVER_NAME'], 'https://'.$_SERVER['SERVER_NAME'], $output);
            $response->setContent($output);
        }

        return $response;
    }
}
