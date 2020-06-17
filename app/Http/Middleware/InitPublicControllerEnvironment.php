<?php

namespace App\Http\Middleware;

use App\Logic\Template\Breadcrumb;
use App\Logic\Template\Menu\Menus;
use App\Logic\Template\Menu\PageMenu;
use Illuminate\Http\Request;

class InitPublicControllerEnvironment
{
    /** Handle an incoming request to validate locale in conjunction with route in order to
     * abort backend routes with unsupported backend locale and vise versa.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle(Request $request, \Closure $next) {
        //common variables
        // get main menu reference to display the menu.
        $mainMenu = PageMenu::initMenu(Menus::MENU_ID_MAIN);
        \View::share('mainMenu',  $mainMenu);
        
        $breadcrumb = Breadcrumb::_get();
        \View::share('breadcrumb',  $breadcrumb);
        
        \View::share('routeBaseName', myApp()->getConfig('publicRouteBaseName'));
        \View::share('baseUrl', myApp()->getConfig('publicBaseUrl'));
        \View::share('transBaseName', myApp()->getConfig('publicTransBaseName'));
        \View::share('viewBasePath', myApp()->getConfig('publicViewBasePath'));
        \View::share('layoutBasePath', myApp()->getConfig('publicLayoutBasePath'));
        \View::share('assetBasePath', myApp()->getConfig('publicAssetBasePath'));
        
        return $next($request);
    }
}
