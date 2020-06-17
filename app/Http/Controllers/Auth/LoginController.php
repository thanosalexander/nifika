<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers{
        login as baseLogin;
    }

    /** Where to redirect users after login.
     * @var string */
//    protected $redirectTo = '/home';

    /** Create a new controller instance.
     * @return void */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    /** Get the login username to be used by the controller.
     * @return string */
    public function username()
    {
        return 'username';
    }
    
    /** Get the post register / login redirect path.
     * @return string */
    public function redirectPath(){
        return route(myApp()->getConfig('adminRouteBaseName').'.home');
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect()->route('public.home');
    }
    
    /** Overwrite this to set our limits. */
    protected function hasTooManyLoginAttempts($request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), 10, 1
        );
    }
}
