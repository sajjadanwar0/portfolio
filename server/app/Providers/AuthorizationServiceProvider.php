<?php

namespace App\Providers;

use App\Repositories\Auth\Authorization;
use App\Repositories\Auth\PermissionRegistrar;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Authorization::$registersInGate) {
            app(PermissionRegistrar::class)->register();
        }
    }
}
