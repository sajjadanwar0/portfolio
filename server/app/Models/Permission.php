<?php

namespace App\Models;

use App\Traits\ClearsCachedPermissions;
use App\Traits\HasRoles;
use App\Traits\HasUsers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasRoles, HasUsers, ClearsCachedPermissions;

    protected $fillable = ['name', 'label'];

    public function organizers(): BelongsToMany
    {
        return $this->belongsToMany(CelebrityOrganizer::class, 'organizer_permissions', 'permission_id', 'organizer_id');
    }

}
