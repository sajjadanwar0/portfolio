<?php


namespace App\Traits;


use App\Models\PermissionPivot;
use App\Repositories\Auth\Authorization;
use Illuminate\Support\Collection;

trait HasPermissions
{
    /**
     * The belongsToMany permissions relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Authorization::permissionModel())->using(PermissionPivot::class);
    }




}
