<?php

namespace App\Providers;

use App\Providers\Services\FakePaymentsServiceExternal;
use App\Providers\Services\IExternalPaymentService;
use App\Providers\Services\CourseService;
use App\Providers\Services\ICourseService;
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
        $this->app->bind(IExternalPaymentService::class, function($app) { return new FakePaymentsServiceExternal(); });
        $this->app->bind(ICourseService::class, function($app) { return new CourseService(); });
    }
}
