<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use YaroslavMolchan\Rbac\Models\Permission;
use YaroslavMolchan\Rbac\Models\Role;

class PermissionsGroup extends Model
{
    protected $fillable = ['name', 'module'];

    public function roles() {
    	return $this->belongsToMany(Role::class);
    }

    public function permissions() {
    	return $this->belongsToMany(Permission::class);
    }

    /**
     * @author MY
     * @param int|Role $role
     */
    public function addRole($role) {
        $this->roles()->attach($role);
    }

    /**
     * @author MY
     * @param int|Role $role
     */
    public function removeRole($role) {
        $this->roles()->detach($role);
    }

    /**
     * @author MY
     * @param int|Permission $permission
     */
    public function addPermission($permission) {
        $this->permissions()->attach($permission);
    }

    /**
     * @author MY
     * @param int|Permission $permission
     */
    public function removePermission($permission) {
        $this->permissions()->detach($permission);
    }
}
