<?php


namespace App\Services;


class SmsCodeService
{
    public static function generateCode(): int
    {
        $code = mt_rand(1000, 9999);

        if (session()->has('sms_code')){
            session()->forget('sms_code');
        }
        session()->put('sms_code', $code);
        return (int)$code;
    }
}
