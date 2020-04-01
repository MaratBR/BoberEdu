<?php

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
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@currentUser');
});

Route::group([
    'prefix' => 'users'
], function ($q) {
    Route::get('{id}', 'UserController@get');
});

Route::group([
    'prefix' => 'courses'
], function ($r) {
    Route::post('{course}/units', 'CourseController@updateUnits');

    Route::get('{course}/attendance', 'CourseAttendanceController@get');
    Route::post('{course}/attendance/join', 'CourseAttendanceController@join');
    Route::post('{course}/attendance/purchase', 'CourseAttendanceController@purchase');
});

Route::resource('courses', 'CourseController')->only(['destroy', 'update', 'show', 'store', 'index']);
Route::resource('users', 'UserController')->only(['update', 'show', 'index']);
Route::resource('lessons', 'LessonController')->only(['store', 'update', 'show', 'index', 'destroy']);

