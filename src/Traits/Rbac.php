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
     * Assign role to a user.
     *
     * @param int|Role $role
     */
    public function assingRole($role) {
        $this->roles()->attach($role);
    }

    /**
     * Revoke user role.
     *
     * @param int|Role $role
     */
    public function revokeRole($role) {
        $this->roles()->detach($role);
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        $roles = $this->roles()->pluck('slug')->toArray();

        return in_array($role, $roles);
    }

    /**
     * @param string $operation
     * @return bool
     */
    public function canDo($operation)
    {
        $roles = $this->roles;
        $permissions = [];
        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $role->permissionsArray());
        }
        $permissions = array_unique($permissions);

        return in_array($operation, $permissions);
    }
}