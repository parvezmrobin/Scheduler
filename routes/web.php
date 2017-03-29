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

Route::get('/', function (){
    return view('home');
})->middleware('auth');

Auth::routes();

Route::get('/home', function (){
    return view('home');
})->middleware('auth');

Route::get('/circle', function(){
    return view('Circle');
})->middleware('auth');

Route::get('/Approval',function(){
    return view('approval');
})->middleware('auth');

Route::get('/Settings', function(){
    return view('Settings');
})->middleware('auth');

Route::get('/CreateTask',function(){
    return view('CreateTask');
})->middleware('auth');

Route::get('/task/{id}',function($id){
    return view('TaskShow');
})->middleware('auth');

Route::get('/api/v1/token', function (Request $request)
{
    if(!Auth::check())
        return response()->json(['error' => 'unauthenticated'], 401);
    $token = JWTAuth::fromUser(Auth::user());
    if(!$token)
        return response()->json(['error' => 'could_not_create_token'], 500);
    return response()->json(compact('token'));
});
