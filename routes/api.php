<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@currentUser');
});


Route::resource('courses', 'CourseController')->only(['destroy', 'update', 'show','store', 'index']);
Route::resource('users', 'UserController')->only(['update', 'show', 'index']);
Route::resource('units', 'UnitController')->only(['store', 'update', 'show', 'index', 'destroy']);
Route::resource('lessons', 'LessonController')->only(['store', 'update', 'show', 'index', 'destroy']);


Route::fallback(function (Request $request) {
    return response()->json(['message' => 'Route not found'], 404);
});
