<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use YaroslavMolchan\Rbac\Models\Permission;
use YaroslavMolchan\Rbac\Models\Role;

class PermissionGroup extends Model
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
    public function attachRole($role) {
        if (!($role instanceof Role) && ctype_digit($role)) {
            $role = Role::find($role);
        }

        $this->roles()->attach($role);

        foreach ($this->permissions as $permission) {
            try {
                $role->attachPermission($permission);
            }
            catch (QueryException $e) {
                //Catch "UNIQUE constraint failed" exceptions, may be groups with the same permissions
                //And when it happens we get error, but now we skip it and continue working
            }
        }
    }

    /**
     * @param int|Role $role
     */
    public function detachRole($role) {
        if (!($role instanceof Role) && ctype_digit($role)) {
            $role = Role::find($role);
        }

        $this->roles()->detach($role);

        foreach ($this->permissions as $permission) {
            $role->detachPermission($permission);
        }
    }

    /**
     * @param int|Permission $permission
     */
    public function attachPermission($permission) {
        if (!($permission instanceof Permission) && ctype_digit($permission)) {
            $permission = Permission::find($permission);
        }

        $this->permissions()->attach($permission);

        foreach ($this->roles as $role) {
            /** @var Role $role */
            try {
                $role->attachPermission($permission);
            }
            catch (QueryException $e) {
                //Catch "UNIQUE constraint failed" exceptions, may be groups with the same permissions
                //And when it happens we get error, but now we skip it and continue working
            }
        }
    }

    /**
     * @param int|Permission $permission
     */
    public function detachPermission($permission) {
        if (!($permission instanceof Permission) && ctype_digit($permission)) {
            $permission = Permission::find($permission);
        }

        $this->permissions()->detach($permission);

        foreach ($this->roles as $role) {
            /** @var Role $role */
            $role->detachPermission($permission);
        }
    }
}
