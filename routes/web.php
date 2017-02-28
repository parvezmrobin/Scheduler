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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/test', function ()
{
    return view('test');
});
Route::get('/privacy', function(){
    return view('mimtest');
});
Route::get('/circle', function(){
    return view('Circle');
});
route::get('/circleRead',function(){
    return view('CircleRead');
});
route::get('/circleUpdate',function(){
    return view('CircleUpdate');
});
route::get('/circleDelete',function(){
    return view('CircleDelete');
});

Route::get('/api/v1/token', function (Request $request)
{
    if(!Auth::check())
        return response()->json(['error' => 'unauthenticated'], 401);
    $token = JWTAuth::fromUser(Auth::user());
    if(!$token)
        return response()->json(['error' => 'could_not_create_token'], 500);
    return response()->json(compact('token'));
});
