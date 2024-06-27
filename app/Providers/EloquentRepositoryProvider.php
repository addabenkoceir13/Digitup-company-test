<?php

namespace App\Providers;

use App\Repositories\Task\RepositoryTask;
use App\Repositories\Tasks\TaskIntrface;
use App\Repositories\User\RepositoryUser;
use App\Repositories\Users\UserIntrface;
use Illuminate\Support\ServiceProvider;

class EloquentRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserIntrface::class, RepositoryUser::class);
        $this->app->bind(TaskIntrface::class, RepositoryTask::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
