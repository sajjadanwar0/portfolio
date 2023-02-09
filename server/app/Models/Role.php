<?php

namespace App\Models;

use App\Repositories\Auth\Authorization;
use App\Traits\HasUsers;
use App\Traits\ManagesPermissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use ManagesPermissions,HasUsers;

    protected $fillable=['name','label'];

}
