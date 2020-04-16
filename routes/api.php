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
], function () {
    Route::get('{id}', 'UserController@get');
});

Route::group([
    'prefix' => 'courses'
], function () {
    Route::put('{course}/units', 'CourseController@updateUnits');
    Route::get('{course}/lessons', 'CourseController@lessons');
    Route::get('{course}/units', 'CourseController@units');

    Route::group([
        'prefix' => 'categories'
    ], function () {

    });
});

Route::resource('courses', 'CourseController')->only(['destroy', 'update', 'show', 'store', 'index']);
Route::resource('users', 'UserController')->only(['update', 'show', 'index']);
Route::resource('lessons', 'LessonsController')->only(['store', 'update', 'show', 'destroy']);

Route::group([
    'prefix' => 'enrollment'
], function () {
    Route::patch('{course}/enroll', 'EnrollmentController@enroll');
    Route::patch('{course}/disenroll', 'EnrollmentController@disenroll');
    Route::get('{course}/status', 'EnrollmentController@status');
    Route::get('yours', 'EnrollmentController@listEnrollments');
});



Route::group([
    'prefix' => 'payments'
], function () {
    Route::patch('{course}/pay', 'PaymentsController@create');
    Route::patch('{course}/disenroll', 'EnrollmentController@disenroll');
    Route::get('yours', 'EnrollmentController@listEnrollments');
});


Route::group([
    'prefix' => 'teachers'
], function () {
    Route::get('{teacher}', 'TeacherController@get');
    Route::post('', 'TeacherController@create');
});

