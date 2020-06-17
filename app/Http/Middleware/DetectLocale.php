<?php

namespace App\Http\Middleware;

use App\Logic\Locales\AdminLocale;
use Closure;
use Illuminate\Http\Request;
use LaravelLocalization;

class DetectLocale
{
    /**
     * Handle an incoming request to detect the current admin locale
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){
        if(myApp()->hasLanguages()){
            $route = $request->route();
            if(!empty($route)){
                $routeBase = array_first(explode('.', $route ? $route->getName() : ''));
                $isAdminRoute = ($routeBase === myApp()->getConfig('adminRouteBaseName'));
                if($isAdminRoute){
                    LaravelLocalization::setLocale(AdminLocale::get()->getCurrent());
                } else {

                }
            }
        }
        
        return $next($request);
    }
}
