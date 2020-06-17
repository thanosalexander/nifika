<?php

namespace App\Http\Middleware;

use App\Logic\App\EntityManager;
use App\Logic\App\Permission;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorizeUser
{
    /** Handle user`s authorization.
     * Handle an incoming request and pass only if user is authorized.
     * @param Request $request
     * @param \Closure  $next
     * @param string|null  $guard
     * @return mixed */
    public function handle($request, Closure $next, $guard = '')
    {
        $result = false;
        $user = auth()->user();
        $result = $this->checkAdminUserAuthorization($request, $user);
        
        if($result){
            return $next($request);
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
    
    /** Check admin,officer,secretariat user`s authorization for the given request.
     * Handle an incoming request and pass only if user is authorized.
     * @param Request $request
     * @param User $user
     * @return boolean */
    protected function checkAdminUserAuthorization($request, $user)
    {
        $result = false;
        $routeName = $request->route()->getName();
        $entity = $request->route('entity');
        $routeBase = myApp()->getConfig('adminRouteBaseName');
        switch($routeName){
            case "{$routeBase}.modelLocale.update":
                $result = allow(Permission::CHANGE_MODEL_LOCALE);
                break;
            case "{$routeBase}.adminLocale.update":
                $result = allow(Permission::CHANGE_ADMIN_LOCALE);
                break;
            case "{$routeBase}.home":
                $result = true;
                break;
            case "{$routeBase}.entity.list":
            case "{$routeBase}.entity.listdata":
                $result = allow(Permission::LIST_ENTITY, $entity);
                break;
            case "{$routeBase}.entity.create":
            case "{$routeBase}.entity.store":
                $result = allow(Permission::CREATE_ENTITY, $entity);
                break;
            case "{$routeBase}.entity.edit":
            case "{$routeBase}.entity.update":
            case "{$routeBase}.entity.switchStatus":
            case "{$routeBase}.entity.editOrder":
            case "{$routeBase}.entity.updateOrder":
            case "{$routeBase}.entity.destroy":
                $model = null;
                $modelClass = EntityManager::entityRefererModel($entity);
                if(!is_null($modelClass)) {
                    $model = $modelClass::where('id', '=', $request->route('id'))->first();
                }
//                switch ($entity) {
//                    default:
//                        break;
//                }
                switch ($routeName) {
                    case "{$routeBase}.entity.destroy":
                        $result = allow(Permission::DELETE_ENTITY, $entity, $model);
                        break;
                    default:
                        $result = allow(Permission::EDIT_ENTITY, $entity, $model);
                        break;
                }
                
                break;
            case "{$routeBase}.settings.edit":
            case "{$routeBase}.settings.update":
                $settingsGroup = $request->route('settingGroup');
                switch ($settingsGroup) {
//                    case Setting::GROUP_GENERAL:
//                    case Setting::GROUP_CONTACT_PAGE:
                    default:
                        $result = allow(Permission::EDIT_SETTING_GROUP, $settingsGroup);
                        break;
                }
                break;
        }
        
        return $result;
    }
}
