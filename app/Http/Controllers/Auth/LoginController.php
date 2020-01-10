<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\SmsCodeService;
use App\Services\SmsSessionService;
use App\User;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        /**
         * inspect number of login try
         */
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        /**
         * Authorization
         * also check sms_verify
         */
        if ($user = User::where('email', '=', $request->email)->exists()) {
            $user = User::where('email', '=', $request->email)->first();

                if ($user->use_sms_verify == true) {

                    if (!(bool)SmsCodeService::lockTimeIsOver()) {

                        if (session()->exists('attempt_end_time') &&
                            session()->exists('email') &&
                            session()->get('email') == $request->email) {
                            return redirect('/login')->withErrors([
                                "You`r account is blocked for: " . SmsCodeService::remainingLockTime() . " minutes "
                            ]);
                        }
                    }
                    SmsSessionService::storeInSession($request->only('email', 'password'));
                    SmsCodeService::generateCode();
                    return redirect()->route('sms-verify');
                }
        }
        /**
         * Login user
         */
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        /**
         * increment attempt
         */
        $this->incrementLoginAttempts($request);
        /**
         * Auth fail
         */
        return $this->sendFailedLoginResponse($request);
    }
}
