<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use App\Policies\UserPolicy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        Lesson::class => LessonPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
