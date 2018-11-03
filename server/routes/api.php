<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/message-history/{with}','ChatController@getHistory')->middleware('cors')->name("History");
Route::post('/new-message','ChatController@postNewMessage')->middleware('cors')->name("NewMessage");

