<?php

namespace App\Http\Middleware;

use App\Models\User;

class RoleMiddleware extends AuthorizationMiddleware
{
    /**
     * Determine if the user has the required roles to access the route.
     *
     * @param User  $user
     * @param array|null $roles
     * @return bool
     */
    protected function authorize($user, $roles = null)
    {
        return $user->hasAnyRoles($roles);
    }
}
