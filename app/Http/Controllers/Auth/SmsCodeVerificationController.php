<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SmsCodeService;
use App\Services\SmsSessionService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsCodeVerificationController extends Controller
{
    public static $smsSessionfields = [
        'sms_code',
        'email',
        'password',
    ];

    public function show()
    {
        return view('auth.sms_code_verification');
    }

    public function login(Request $request)
    {
        if ((bool)SmsCodeService::toManyAttempt()) {
            SmsSessionService::deleteFromSession(['sms_code', 'password']);
            SmsSessionService::storeInSession(['attempt_end_time' => Carbon::now()]);
            return redirect('/login')->withErrors([
                "You`r account is blocked for: " . SmsCodeService::LOCK_PERIOD_TIME . " minutes "
            ]);
        }
        if ((int)$request->code !== session()->get('sms_code')) {
            SmsCodeService::incrementTypeWrongCode();
            return redirect("sms-verify")->withErrors(["Invalid code." . ' Attempts left : ' . session()->get('attempt_left')]);
        }
        $credentials = [
            'email' => session()->get('email'),
            'password' => session()->get('password'),
        ];
        if (Auth::attempt($credentials)) {
            SmsSessionService::deleteFromSession(static::$smsSessionfields);
            return redirect()->intended('/home');
        }
    }
}
