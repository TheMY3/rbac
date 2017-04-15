<?php
namespace YaroslavMolchan\Rbac\Traits;

use YaroslavMolchan\Rbac\Models\Role;

trait Rbac
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Attach role to user.
     * @param int|Role $role
     */
    public function attachRole($role) {
        $this->roles()->attach($role);
    }

    /**
     * Detach role from user.
     * @param int|Role $role
     */
    public function detachRole($role) {
        $this->roles()->detach($role);
    }

    /**
     * Check if user has current role
     * @param string $slug
     * @return bool
     */
    public function hasRole($slug)
    {
        $roles = $this->roles()->pluck('slug')->toArray();

        return in_array($slug, $roles);
    }

    /**
     * Check if user has permission to current operation
     * @param string $slug
     * @return bool
     */
    public function canDo($slug)
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $permissions = array_merge($permissions, $role->permissionsArray());
        }
        $permissions = array_unique($permissions);

        return in_array($slug, $permissions);
    }
}