<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DetectUnderConstructionMode
{
    /** Handle an incoming request to validate locale in conjunction with route in order to
     * abort backend routes with unsupported backend locale and vise versa.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle(Request $request, Closure $next) {
        $skipUnderConstruction = false;
        $requestShowDemo = $request->get('showDemo', null);
        $sessionShowDemo = $request->session()->get('showDemo');
//        dump($requestShowDemo);
//        dump($sessionShowDemo);
//        dump('-----------------------------------');
        if($requestShowDemo === 'show') {
            $skipUnderConstruction = true;
            if(empty($sessionShowDemo)){
//                dump('CREATE show Demo session');
                $request->session()->set('showDemo', '1');
            }
        } elseif ($requestShowDemo === 'hide') {
            if(!empty($sessionShowDemo)){
//                dump('DELETE show Demo session');
                $request->session()->forget('showDemo');
            }
        }
        if(!empty($sessionShowDemo)){
            $skipUnderConstruction = true;
        }
//        dump($requestShowDemo);
//        dump($sessionShowDemo);
//        dump($skipUnderConstruction);
//        echo 'ddddd';
//        die();
        $isUnderConstructionMode = (ss(Setting::SS_UNDER_CONSTRUCTION_MODE_ENABLED));
        
        if($isUnderConstructionMode && !$skipUnderConstruction) {
            return response()->view('pages.public.underConstruction')
                    ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
        }
        return $next($request);
    }
}
