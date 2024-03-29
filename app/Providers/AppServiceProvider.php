<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        //

        Inertia::share('permissions', function () {
            return auth()->user()?->getPermissionsViaRoles() ?? [];
        });

        Inertia::share('statuses', function () {
            return config('default.task_statuses');
        });
    }
}
