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
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@currentUser');
});


Route::group([
    'prefix' => 'courses'
], function ($r) {
    Route::post('{course}/units', 'CourseController@updateUnits');

    Route::get('{course}/attendance/status', 'CourseAttendanceController@status');
    Route::post('{course}/attend', 'CourseAttendanceController@attend');
    Route::get('{course}/attendance', 'CourseAttendanceController@get');
    Route::post('{course}/attendance/submit', 'CourseAttendanceController@submit');
});

Route::resource('courses', 'CourseController')->only(['destroy', 'update', 'show','store', 'index']);
Route::resource('users', 'UserController')->only(['update', 'show', 'index']);
Route::resource('lessons', 'LessonController')->only(['store', 'update', 'show', 'index', 'destroy']);

