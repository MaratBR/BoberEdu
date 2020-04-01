<?php

namespace App\Providers;

use App\Providers\Services\Abs\IJoinCourseService;
use App\Providers\Services\Abs\IPurchasesService;
use App\Providers\Services\Abs\IRedirectService;
use App\Providers\Services\Abs\IUsersService;
use App\Providers\Services\JoinCourseService;
use App\Providers\Services\FakeExternalPaymentsService;
use App\Providers\Services\Abs\IExternalPaymentService;
use App\Providers\Services\CourseService;
use App\Providers\Services\Abs\ICourseService;
use App\Providers\Services\PurchaseService;
use App\Providers\Services\RedirectService;
use App\Providers\Services\UsersService;
use Illuminate\Support\ServiceProvider;
use function foo\func;

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
            IExternalPaymentService::class,
            FakeExternalPaymentsService::class
        );

        $this->app->singleton(
            ICourseService::class,
            CourseService::class
        );

        $this->app->singleton(
            IUsersService::class,
            UsersService::class
        );

        $this->app->singleton(
            IPurchasesService::class,
            PurchaseService::class
        );

        $this->app->singleton(
            IJoinCourseService::class,
            JoinCourseService::class
        );

        $this->app->singleton(
            IRedirectService::class,
            RedirectService::class
        );
    }
}
