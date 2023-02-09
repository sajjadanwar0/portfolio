<?php


namespace App\Traits;


use App\Models\RolePivot;
use App\Repositories\Auth\Authorization;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

trait HasRoles
{
    /**
     * The belongsToMany roles relationship.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Authorization::roleModel())->using(RolePivot::class);
    }

    public function getRoleNames(): Collection
    {
        return $this->roles->pluck('name');
    }

    public function getAllPermissions()
    {
        return auth()->user()->permissions->pluck('name');
    }


}
