<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * Replaces Zizaco\Entrust\EntrustPermission (package abandoned).
 */
class Permission extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
    }
}
