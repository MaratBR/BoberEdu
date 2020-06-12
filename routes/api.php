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
    Route::patch('profile', 'UserController@updateProfile');
    Route::put('profile/status', 'UserController@setStatus');
    Route::get('profile/settings', 'UserController@settings');
    Route::put('profile/avatar', 'UserController@uploadAvatar');
    Route::get('username-taken/{username}', 'UserController@checkUsername');
    Route::get('{id}', 'UserController@get');
    Route::get('{id}/profile', 'UserController@profile');
});

Route::group([
    'prefix' => 'courses'
], function () {
    Route::get('search', 'SearchController@search');
    Route::put('{course}/units', 'CourseController@updateUnits');
    Route::put('{course}/ordnung-muss-sein', 'CourseController@updateLessonsOrder');
    Route::get('{course}/lessons', 'CourseController@lessons');

    Route::delete('{course}/rate', 'CourseController@removeRate');
    Route::put('{course}/rate', 'CourseController@setRate');
    Route::get('{course}/rate', 'CourseController@getRate');
    Route::get('units/{id}', 'CourseController@getUnit');

    Route::group([
        'prefix' => 'categories'
    ], function () {
        Route::post('', 'CourseController@createCategory');
        Route::put('{category}', 'CourseController@updateCategory');
        Route::get('', 'CourseController@categories');
        Route::get('{category}', 'CourseController@category');
        Route::get('{category}/courses', 'CourseController@categoryCourses');
    });
});

Route::resource('courses', 'CourseController')->only(['destroy', 'update', 'show', 'store', 'index']);
Route::resource('users', 'UserController')->only(['update', 'show', 'index']);


Route::group([
    'prefix' => 'lessons'
], function () {
    Route::get('{lesson}/admin', 'LessonsController@showAdmin');
});
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
    Route::get('', 'PaymentsController@payments');
});


Route::group([
    'prefix' => 'teachers'
], function () {
    Route::put('approval-form', 'TeacherController@sendApprovalForm');
    Route::get('approval-form', 'TeacherController@approvalStatus');
    Route::post('assignment/{teacher}/{course}', 'TeacherController@assign');
    Route::delete('assignment/{teacher}/{course}', 'TeacherController@revoke');

    Route::get('{teacher}', 'TeacherController@get');
    Route::post('', 'TeacherController@create');
});

Route::group([
    'middleware' => 'admin',
    'prefix' => 'admin'
], function () {

    Route::get('overview', 'Admin\OverviewController@get');

    Route::group([
        'prefix' => 'audit'
    ], function () {
        Route::get('all', 'Admin\AuditController@paginate');
    });

    Route::group([
        'prefix' => 'users'
    ], function () {
        Route::get('', 'Admin\UsersController@paginate');
        Route::get('search', 'Admin\UsersController@search');
        Route::get('{id}', 'Admin\UsersController@get');
        Route::put('{id}', 'Admin\UsersController@update');
        Route::put('{id}/avatar', 'Admin\UsersController@uploadAvatar');
        Route::put('{id}/admin', 'Admin\UsersController@promote');
        Route::post('', 'Admin\UsersController@create');
    });

    Route::group([
        'prefix' => 'categories'
    ], function () {
        Route::put('{id}/image', 'Admin\CategoriesController@uploadImage');
        Route::post('', 'Admin\CategoriesController@create');
        Route::put('{id}', 'Admin\CategoriesController@update');
    });

    Route::group([
        'prefix' => 'courses'
    ], function () {
        Route::get('search', 'Admin\CoursesController@search');
        Route::post('', 'Admin\CoursesController@create');
        Route::put('{id}', 'Admin\CoursesController@update');
        Route::put('{id}/units', 'Admin\CoursesController@updateUnits');
        Route::put('{id}/image', 'Admin\CoursesController@uploadImage');
        Route::delete('{id}', 'Admin\CoursesController@delete');
        Route::get('units/{id}', 'Admin\CoursesController@unit');
    });

    Route::group([
        'prefix' => 'lessons'
    ], function () {
        Route::post('', 'Admin\LessonsController@create');
        Route::get('{id}', 'Admin\LessonsController@get');
        Route::put('{id}', 'Admin\LessonsController@update');
        Route::delete('{id}', 'Admin\LessonsController@delete');
    });


    Route::group([
        'prefix' => 'teachers'
    ], function () {

        Route::get('approval-form', 'Admin\TeachersController@approvalForms');
        Route::get('approval-form/{id}', 'Admin\TeachersController@getTeacherApplication');
        Route::put('approval-form/{id}/approve', 'Admin\TeachersController@approveForm');
        Route::put('approval-form/{id}/reject', 'Admin\TeachersController@disapproveForm');

        Route::get('', 'Admin\TeachersController@paginate');
        Route::get('search', 'Admin\TeachersController@search');
        Route::post('', 'Admin\TeachersController@create');
        Route::get('{id}', 'Admin\TeachersController@get');
        Route::put('{id}', 'Admin\TeachersController@update');
        Route::delete('{id}', 'Admin\TeachersController@delete');
        Route::put('{id}/avatar', 'Admin\TeachersController@uploadAvatar');

        Route::post('{id}/course/{courseId}', 'Admin\LessonsController@assign');
        Route::delete('{id}/course/{courseId}', 'Admin\LessonsController@revoke');

    });

    Route::group([
        'prefix' => 'assignments'
    ], function () {
        Route::delete('{teacherId}/{courseId}/assignment', 'Admin\TeacherAssignmentController@revoke');
        Route::put('{teacherId}/{courseId}/assignment', 'Admin\TeacherAssignmentController@assign');

    });
});


Route::group([
    'prefix' => 'teacher-dashboard',
    'middleware' => 'teachers-only'
], function () {
    Route::get('', 'Teacher\DashboardController@get');

    Route::post('courses', 'Teacher\CourseController@createCourse');
    Route::put('courses/{id}', 'Teacher\CourseController@updateCourse');
    Route::get('courses/{id}', 'Teacher\CourseController@getCourse');
    Route::put('courses/{id}/image', 'Teacher\CourseController@uploadCourseImage');
    Route::put('courses/{id}/units', 'Teacher\CourseController@updateUnitsOrder');
    Route::put('courses/{id}/lessons-order', 'Teacher\CourseController@updateLessonsOrder');

    Route::post('lessons', 'Teacher\CourseController@createLesson');
    Route::put('lessons/{id}', 'Teacher\CourseController@updateLesson');
    Route::get('lessons/{id}', 'Teacher\CourseController@getLesson');
});
