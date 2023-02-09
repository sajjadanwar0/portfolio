<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePivot extends Model
{
    /**
     * Flush the roles relation on attach / detach.
     *
     * @var string
     */
    protected static $flushingRelation = 'roles';
}
