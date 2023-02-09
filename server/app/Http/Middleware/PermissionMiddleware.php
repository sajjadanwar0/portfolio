<?php

namespace App\Http\Middleware;

use App\Http\Middleware\AuthorizationMiddleware;
use App\Models\User;

class PermissionMiddleware extends AuthorizationMiddleware
{
    /**
     * Determine if the user has the required permission to access the route.
     *
     * @param User  $user
     * @param array|null $permissions
     * @return bool
     */
    protected function authorize($user, $permissions = null)
    {
        return $user->hasAnyPermissions($permissions);
    }
}
