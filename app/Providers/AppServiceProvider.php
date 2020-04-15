<?php

namespace App\Providers;

use App\Services\Abs\IEnrollmentService;
use App\Services\Abs\IPaymentsService;
use App\Services\Implementation\EnrollmentService;
use App\Services\Implementation\PaymentsService;
use App\Services\Implementation\UserCoursesService;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IExternalPaymentService;
use App\Services\Abs\IUserCoursesService;
use App\Services\Abs\ILessonsService;
use App\Services\Abs\IPurchasesService;
use App\Services\Abs\IRedirectService;
use App\Services\Abs\IUsersService;
use App\Services\Implementation\CourseService;
use App\Services\Implementation\FakeExternalPaymentsService;
use App\Services\Implementation\LessonsService;
use App\Services\Implementation\PurchaseService;
use App\Services\Implementation\RedirectService;
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
    }
}
