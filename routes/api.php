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
    Route::put('profile/status', 'UserController@setStatus');
    Route::get('profile/settings', 'UserController@settings');
    Route::put('profile/avatar', 'UserController@uploadAvatar');
    Route::get('username-taken/{username}', 'UserController@checkUsername');
    Route::get('{id}', 'UserController@get');
    Route::patch('{id}', 'UserController@update');
    Route::get('{id}/profile', 'UserController@profile');
});

Route::group([
    'prefix' => 'courses'
], function () {
    Route::put('{course}/units', 'CourseController@updateUnits');
    Route::get('{course}/lessons', 'CourseController@lessons');
    Route::get('{course}/units', 'CourseController@units');

    Route::delete('{course}/rate', 'CourseController@removeRate');
    Route::put('{course}/rate', 'CourseController@setRate');
    Route::get('{course}/rate', 'CourseController@getRate');

    Route::group([
        'prefix' => 'categories'
    ], function () {
        Route::get('', 'CourseController@categories');
        Route::get('{category}', 'CourseController@category');
        Route::get('{category}/courses', 'CourseController@categoryCourses');
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
    Route::patch('course/{course}/pay', 'PaymentsController@create');
});


Route::group([
    'prefix' => 'teachers'
], function () {
    Route::get('{teacher}', 'TeacherController@get');
    Route::post('', 'TeacherController@create');
    Route::post('assignment/{teacher}/{course}', 'TeacherController@assign');
    Route::delete('assignment/{teacher}/{course}', 'TeacherController@revoke');
});

