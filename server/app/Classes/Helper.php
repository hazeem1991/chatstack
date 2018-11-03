<?php
namespace App\Classes;
use App\Http\Model\Log;

class Helper 
{
    public static function log($logData)
    {
        $endpoint=\Route::currentRouteName();
        $logData['endpoint']=$endpoint;
        Log::create($logData);
    }
    public static function getLog()
    {
        $log=Log::orderBy('created_at',"DESC")->get();
        return $log ;
    }
}
