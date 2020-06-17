<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class InitAdminEnvironment
{
    /** Handle an incoming request to validate locale in conjunction with route in order to
     * abort backend routes with unsupported backend locale and vise versa.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle(Request $request, \Closure $next) {
        \View::share('routeBaseName', myApp()->getConfig('adminRouteBaseName'));
        \View::share('baseUrl', myApp()->getConfig('adminBaseUrl'));
        \View::share('transBaseName', myApp()->getConfig('adminTransBaseName'));
        \View::share('viewBasePath', myApp()->getConfig('adminViewBasePath'));
        \View::share('layoutBasePath', myApp()->getConfig('adminLayoutBasePath'));
        \View::share('assetBasePath', myApp()->getConfig('adminAssetBasePath'));
        
        return $next($request);
    }
}
