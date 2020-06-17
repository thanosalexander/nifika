<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Logic\Locales\AppLocales;

class ValidateLocale
{
    /** Handle an incoming request to validate locale in conjunction with route in order to
     * abort backend routes with unsupported backend locale and vise versa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle(Request $request, Closure $next) {
        if(myApp()->hasLanguages()){
            $route = $request->route();
            $routeBase = array_first(explode('.', $route ? $route->getName() : ''));
            $isAdminRoute = ($routeBase === myApp()->getConfig('adminRouteBaseName'));
            $urlLocale = $request->segment(1);
            if(\App\Logic\Locales\AppLocales::isSupported($urlLocale) && !empty($route)){
                if($isAdminRoute){
                    if(!AppLocales::isBackend($urlLocale)){
                        \LaravelLocalization::setLocale(AppLocales::defaultBackend());
                        abort(Response::HTTP_NOT_FOUND);
                    }
                } else {
                    if(!\App\Logic\Locales\AppLocales::isFrontend($urlLocale)){
                        \LaravelLocalization::setLocale(AppLocales::defaultFrontend());
                        abort(Response::HTTP_NOT_FOUND);
                    }
                }
            }
        }
        return $next($request);
    }
}
