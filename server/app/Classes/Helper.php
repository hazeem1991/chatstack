<?php
namespace App\Classes;
use App\Http\Model\Log;

class Helper 
{
    public static function log($logData)
    {
        $endpint=\Route::currentRouteName();
        $logData['endpint']=$endpint;
        Log::create($logData);
    }
    public static function getLog()
    {
        $log=Log::orderBy('created_at',"DESC")->get();
        return $log ;
    }
}
