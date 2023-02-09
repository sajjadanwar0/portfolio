<?php


namespace App\Traits;


use App\Models\UserPivot;
use App\Repositories\Auth\Authorization;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasUsers
{
    /**
     * The belongsToMany users relationship.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Authorization::userModel())->using(UserPivot::class);
    }
}
