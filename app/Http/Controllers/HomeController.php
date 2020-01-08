<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function showSettings()
    {
        $user = auth()->user();
        return view('settings', compact('user'));
    }

    public function storeSettings()
    {
        $user = auth()->user();

        if ($user->use_sms_verify == false) {
            $user->use_sms_verify = true;
            $user->save();
        } else {
            $user->use_sms_verify = false;
            $user->save();
        }
        return back();
    }
}
