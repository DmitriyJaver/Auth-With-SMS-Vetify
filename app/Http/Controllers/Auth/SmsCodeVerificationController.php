<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SmsCodeService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsCodeVerificationController extends Controller
{

    public function show(){

        return view('auth.sms_code_verification');
    }

    public function login(Request $request)
    {
        if ((int)$request->code !== session()->get('sms_code')) {
            return redirect("sms-verify")->withErrors(["Invalid code." . ' Attempts left : ']);
        } else {
            session()->forget('sms_key');
        }
        $credentials = [
            'email' => session()->get('email'),
            'password' => session()->get('password'),
        ];
        if (Auth::attempt($credentials)) {
            session()->forget('');
            return redirect()->intended('/home');
        }
    }
}
