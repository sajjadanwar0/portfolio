<?php


namespace App\Traits;

use App\Repositories\Auth\Authorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait Authorizable
{
    use HasRoles, HasPermissions;

    /**
     * Assign the given role to the user.
     *
     * @param string|Model $role
     *
     * @return Model
     */
    public function assignRole($role): Model
    {
        if (!$role instanceof Model) {
            $role = Authorization::role()->whereName($role)->firstOrFail();
        }

        return $this->roles()->save($role);
    }



//    public function assignRoles(...$roles)
//    {
//        $roles = collect($roles)
//            ->flatten()
//            ->map(function ($role) {
//                if (empty($role)) {
//                    return false;
//                }
//
//                return $this->getStoredRole($role);
//            })
//            ->filter(function ($role) {
//                return $role instanceof Role;
//            })->map->id
//            ->all();
//
//        $model = $this->getModel();
//
//        if ($model->exists) {
//            $this->roles()->sync($roles, false);
//            $model->load('roles');
//        } else {
//            $class = \get_class($model);
//
//            $class::saved(
//                function ($object) use ($roles, $model) {
//                    static $modelLastFiredOn;
//                    if ($modelLastFiredOn !== null && $modelLastFiredOn === $model) {
//                        return;
//                    }
//                    $object->roles()->sync($roles, false);
//                    $object->load('roles');
//                    $modelLastFiredOn = $object;
//                });
//        }
//
//        $this->forgetCachedPermissions();
//
//        return $this;
//    }

    /**
     * Removes the specified role from the user.
     *
     * @param string|Model $role
     *
     * @return int
     */
    public function removeRole($role): int
    {
        if (!$role instanceof Model) {
            $role = Authorization::role()->whereName($role)->firstOrFail();
        }

        return $this->roles()->detach($role);
    }


    /**
     * Determine if the user has the given role.
     *
     * @param string|Model $role
     *
     * @return bool
     */
    public function hasRole($role): bool
    {
        if (empty($role)) {
            return false;
        }

        $this->loadMissing('roles');

        return $role instanceof Model
            ? $this->roles->contains($role)
            : $this->roles->contains('name', $role);
    }

    /**
     * Determine if the user has all of the given roles.
     *
     * @param array $roles
     *
     * @return bool
     */
    public function hasRoles(array $roles): bool
    {
        $roles = collect($roles);

        if ($roles->isEmpty()) {
            return false;
        }

        return $roles->filter(function ($role) {
                return $this->hasRole($role);
            })->count() === $roles->count();
    }

    /**
     * Determine if the user has any of the given roles.
     *
     * @param array $roles
     *
     * @return bool
     */
    public function hasAnyRoles(array $roles): bool
    {
        return collect($roles)->filter(function ($role) {
            return $this->hasRole($role);
        })->isNotEmpty();
    }

    /**
     * Determine if the user has the given permission.
     *
     * @param string|Model $permission
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            // If we've been given a string, then we can
            // assume it's the permissions name. We will
            // attempt to fetch it from the database.
            $permission = Authorization::permission()->whereName($permission)->first();
        }

        if (!$permission instanceof Model) {
            return false;
        }

        // Here we will check if the user has been granted
        // explicit this permission explicitly. If so, we
        // can return here. No further check is needed.
        if ($this->permissions()->find($permission->getKey())) {
            return true;
        }

        // Otherwise, we'll determine their permission by gathering
        // the roles that the permission belongs to and checking
        // if the user is a member of the fetched roles.
        $roles = $permission->roles()->get()->map(function ($role) {
            return $role->getKey();
        });

        return $this->roles()
                ->whereIn($this->roles()->getRelatedPivotKeyName(), $roles)
                ->count() > 0;
    }

    /**
     * Determine if the user has all of the given permissions.
     *
     * @param array|Collection $permissions
     *
     * @return bool
     */
    public function hasPermissions($permissions): bool
    {
        $permissions = collect($permissions);

        if ($permissions->isEmpty()) {
            return false;
        }

        return $permissions->filter(function ($permission) {
                return $this->hasPermission($permission);
            })->count() === $permissions->count();
    }

    /**
     * Determine if the user has any of the permissions.
     *
     * @param array|Collection $permissions
     *
     * @return bool
     */
    public function hasAnyPermissions($permissions): bool
    {
        return collect($permissions)->filter(function ($permission) {
            return $this->hasPermission($permission);
        })->isNotEmpty();
    }

    /**
     * Determine if the user does not have the given permission.
     *
     * @param string|Model $permission
     *
     * @return bool
     */
    public function doesNotHavePermission($permission): bool
    {
        return !$this->hasPermission($permission);
    }

    /**
     * Determine if the user does not have all the given permissions.
     *
     * @param array|Collection $permissions
     *
     * @return bool
     */
    public function doesNotHavePermissions($permissions): bool
    {
        return !$this->hasPermissions($permissions);
    }

    /**
     * Determine if the user does not have any of the given permissions.
     *
     * @param array|Collection $permissions
     *
     * @return bool
     */
    public function doesNotHaveAnyPermissions($permissions): bool
    {
        return !$this->hasAnyPermissions($permissions);
    }
}
