<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * Replaces Zizaco\Entrust\EntrustRole (package abandoned, not Laravel 9 compatible).
 */
class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function attachPermission($permission)
    {
        $id = is_object($permission) ? $permission->getKey() : $permission;
        $this->permissions()->attach($id);
    }

    public function syncPermissions($permissions)
    {
        $this->permissions()->sync($permissions);
    }
}
