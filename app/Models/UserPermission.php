<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $guarded = [];

    /**
     * The permissions that belong to the user.
     *
     * @return object
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'id', 'permission_id');
    }

    /**
     * The users that belong to the permission.
     *
     * @return object
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'id', 'user_id');
    }
}
