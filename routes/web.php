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

use Illuminate\Support\Facades\Route;

Route::redirect('/td', '/me/teacher/dashboard?_sFrom=1', 301);
Route::redirect('/a', '/admin/overview?_sFrom=1', 301);


Route::fallback(function () {
    return response()
        ->view('app')
        ->header('Link', 'css/app.css; rel=preload; as=style', false)
        ->header('Link', 'js/app.js; rel=preload; as=script', false);
});


