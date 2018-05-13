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

Route::auth();

Route::get('/', function(\Illuminate\Contracts\Auth\Guard $user) {
    if ($user->check()) {
        return redirect('/home');
    }
});

Route::get('{all}', function (\Illuminate\Contracts\Auth\Guard $user) {
    if (!$user->check()) {
        return view('welcome');
    }
    return view('layouts.app');
})->where('all', '^.*');

