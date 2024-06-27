<?php

namespace App\Providers;

use App\Repositories\Task\RepositoryTask;
use App\Repositories\Tasks\TaskIntrface;
use App\Repositories\User\RepositoryUser;
use App\Repositories\Users\UserIntrface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
