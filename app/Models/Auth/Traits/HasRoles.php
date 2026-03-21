<?php

namespace App\Models\Auth\Traits;

use App\Models\Auth\Role;

/**
 * Minimal replacement for the abandoned Zizaco\Entrust EntrustUserTrait.
 * Provides hasRole() and the roles() relationship used throughout the app.
 */
trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($name)
    {
        foreach ((array) $name as $roleName) {
            if ($this->roles->contains('name', $roleName)) {
                return true;
            }
        }
        return false;
    }

    public function attachRole($role)
    {
        $id = is_object($role) ? $role->getKey() : $role;
        $this->roles()->attach($id);
    }

    public function detachRole($role)
    {
        $id = is_object($role) ? $role->getKey() : $role;
        $this->roles()->detach($id);
    }

    public function syncRoles($roles)
    {
        $this->roles()->sync($roles);
    }

    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }

    public function can($abilities, $arguments = [])
    {
        if (is_string($abilities) && $this->hasPermission($abilities)) {
            return true;
        }
        return parent::can($abilities, $arguments);
    }
}
