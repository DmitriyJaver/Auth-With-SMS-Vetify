<?php


namespace App\Services;


use Carbon\Carbon;

class SmsCodeService
{
    const MAX_NUMBER_OF_TRY = 2;
    const LOCK_PERIOD_TIME = 3;

    public static function generateCode(): int
    {
        $code = mt_rand(1000, 9999);

        if (session()->has('sms_code')) {
            SmsSessionService::deleteFromSession(['sms_code']);
        }
        SmsSessionService::storeInSession(['sms_code' => $code]);
        return (int)$code;
    }

    public static function incrementTypeWrongCode()
    {
        if (session()->has('attempt_left')) {
            $count = session()->get('attempt_left');
            $count--;
            SmsSessionService::storeInSession(['attempt_left' => $count]);
        } else {
            $count = self::MAX_NUMBER_OF_TRY;
            SmsSessionService::storeInSession(['attempt_left' => --$count]);
        }
    }

    public static function toManyAttempt(): bool
    {
        if (session()->has('attempt_left') &&
            session()->get('attempt_left') == 1) {
            SmsSessionService::deleteFromSession(['attempt_left']);
            return true;
        }
        return false;
    }

    public static function lockTimeIsOver(): bool
    {
        if (session()->has('attempt_end_time')) {
            if (self::remainingLockTime() <= 0) {
                SmsSessionService::deleteFromSession(['attempt_end_time', 'email']);
                return true;
            }
        }
        return false;
    }

    public static function remainingLockTime(): int
    {
        $nowTime = Carbon::now();
        if (session()->has('attempt_end_time')) {
            $time = session()->get('attempt_end_time');
            return (int)$timeLeft = self::LOCK_PERIOD_TIME - $time->diffInMinutes($nowTime);
        }
    }


}
