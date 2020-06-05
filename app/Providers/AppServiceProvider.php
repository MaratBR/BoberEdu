<?php

namespace App\Providers;

use App\Services\Abs\IAuditService;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IEnrollmentService;
use App\Services\Abs\ILessonsService;
use App\Services\Abs\IPaymentsService;
use App\Services\Abs\ITeachersService;
use App\Services\Abs\IUploadService;
use App\Services\Abs\IUsersService;
use App\Services\Implementation\AuditService;
use App\Services\Implementation\CourseService;
use App\Services\Implementation\EnrollmentService;
use App\Services\Implementation\LessonsService;
use App\Services\Implementation\PaymentsService;
use App\Services\Implementation\TeachersService;
use App\Services\Implementation\UploadService;
use App\Services\Implementation\UsersService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(
            IEnrollmentService::class,
            EnrollmentService::class
        );

        $this->app->singleton(
            ICourseService::class,
            CourseService::class
        );

        $this->app->singleton(
            ILessonsService::class,
            LessonsService::class
        );

        $this->app->singleton(
            IUsersService::class,
            UsersService::class
        );

        $this->app->singleton(
            IUsersService::class,
            UsersService::class
        );

        $this->app->singleton(
            IPaymentsService::class,
            PaymentsService::class
        );

        $this->app->singleton(
            ITeachersService::class,
            TeachersService::class
        );

        $this->app->singleton(
            IUploadService::class,
            UploadService::class
        );

        $this->app->singleton(
            IAuditService::class,
            AuditService::class
        );
    }
}
