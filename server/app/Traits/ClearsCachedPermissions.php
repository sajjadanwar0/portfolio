<?php


namespace App\Traits;


use App\Repositories\Auth\Authorization;
use App\Repositories\Auth\PermissionRegistrar;

trait ClearsCachedPermissions
{
    public static function bootClearsCachedPermissions()
    {
        if (Authorization::$cachesPermissions) {
            static::saved(function () {
                app(PermissionRegistrar::class)->flushCache();
            });

            static::deleted(function () {
                app(PermissionRegistrar::class)->flushCache();
            });
        }
    }
}
