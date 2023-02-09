<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionPivot extends Model
{
    /**
     * Flush the permissions' relation on attach / detach.
     *
     * @var string
     */
    protected static $flushingRelation = 'permissions';
}
