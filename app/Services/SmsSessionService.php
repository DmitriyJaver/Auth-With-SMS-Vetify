<?php


namespace App\Services;


class SmsSessionService
{
    public static function storeInSession(array $data)
    {
        foreach ($data as $key => $value){
            session()->put($key, $value);
        }
    }
    public static function deleteFromSession(array $data){
        foreach ($data as $key){
            session()->forget($key);
        }
    }
}
