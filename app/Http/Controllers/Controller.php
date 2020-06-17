<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    const STATUS_OK = 'ok';
    const STATUS_FAIL = 'fail';
    
    const RESPONSE_VAR_NOTIFICATION = '_notification';
    
    /** Return a json response that is created using given params.
     * @param string $status Must be the const STATUS_OK or STATUS_FAIL.
     * @param array $customMessages Overrides status messages.
     * @param array|string $data Appended to json with key 'data'.
     * @return JsonResponse */
    protected function getJsonResponse($status, $customMessages = [], $data = false){
        $successMessage = (isset($customMessages[static::STATUS_OK])
                ? $customMessages[static::STATUS_OK]
                :trans(static::$transBaseName . '.form.message.storeSuccess')
                );
        $failMessage = (isset($customMessages[static::STATUS_FAIL])
                ? $customMessages[static::STATUS_FAIL]
                :trans(static::$transBaseName . '.form.message.saveFail')
                );
        $responses = [
            static::STATUS_OK => ['status' => static::STATUS_OK, 'message' => $successMessage],
            static::STATUS_FAIL => ['status' => static::STATUS_FAIL, 'message' => $failMessage],
        ];
        
        $responseData = [
            'status' => $responses[$status]['status'],
            'message' => $responses[$status]['message'],
                ];
        
        if($data !== false){
            $responseData['data'] = $data;
        }
        
        $response = response()->json($responseData);
        //if result is has fail status set http status code as 422
        if ($status == static::STATUS_FAIL) {
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $response;
    }
    
    /** Add common variables to the given view and it is returned it back.
     *  @param \View $view
     * @return \View */
    protected function getHtmlResponse($view){
        return response($view
                    ->with(static::RESPONSE_VAR_NOTIFICATION, $this->flashResponseVariable(static::RESPONSE_VAR_NOTIFICATION))
                    ->with('authUser', auth()->user()))
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0')
                ;
                
    }
    
    /** Write a notification message to session in order to show it to the next view response.
     *  @param string $status
     * @param array $message */
    protected function putResponseNotification($status, $message){
        \Session::put(static::RESPONSE_VAR_NOTIFICATION, [
            'status' => $status,
            'message' => $message
        ]);
    }
    
    /** Return the notification message from the session if exists and remove it from session.
     * @return array|null */
    protected function flashResponseNotification(){
        return $this->flashResponseVariable(static::RESPONSE_VAR_NOTIFICATION);
    }
    
    /** Return the googleTag from the session if exists and remove it from session.
     * @return array|null */
    protected function flashResponseVariable($varName){
        $varValue = null;
        if(\Session::has($varName)){
            $varValue = \Session::get($varName);
            \Session::remove($varName);
        }
        return $varValue;
    }
}
