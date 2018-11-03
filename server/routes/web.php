<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('home');
});
Route::get('/log','HomeController@getLog')->name('GetLog');


// , function () {
//     return view('welcome');
// });

// Auth::routes();

Route::post('init/{user?}', 'ChatController@postInit')->middleware('cors')->name('Init');
