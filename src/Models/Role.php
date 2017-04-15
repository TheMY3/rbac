<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use YaroslavMolchan\Rbac\Models\Permission;
use YaroslavMolchan\Rbac\Models\PermissionGroup;
use YaroslavMolchan\Rbac\Helpers\CacheHelper;

class Role extends Model
{
    protected $fillable = ['slug', 'name'];

    public function getCacheKey() {
        return 'role_'.$this->slug;
    }

    public function users() {
    	return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function permissions() {
    	return $this->belongsToMany(Permission::class);
    }

    public function permissionGroups() {
    	return $this->belongsToMany(PermissionGroup::class);
    }

    /**
     * @param int|Permission $permission
     */
    public function attachPermission($permission) {
        $this->permissions()->attach($permission);
        CacheHelper::clear($this);
    }

    /**
     * @param array $permissions array of Permission objects or id
     */
    public function attachPermissions($permissions) {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * @param int|Permission $permission
     */
    public function detachPermission($permission) {
        $this->permissions()->detach($permission);
        CacheHelper::clear($this);
    }

    /**
     * @param array $permissions array of Permission objects or id
     */
    public function detachPermissions($permissions) {
        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    public function attachGroup($group) {
        if (!($group instanceof PermissionGroup) && ctype_digit($group)) {
            $group = PermissionGroup::find($group);
        }

        $this->permissionGroups()->attach($group);

        foreach ($group->permissions as $permission) {
            try {
                $this->attachPermission($permission);
            }
            catch (QueryException $e) {
                //Catch "UNIQUE constraint failed" exceptions, may be groups with the same permissions
                //And when it happens we get error, but now we skip it and continue working
            }
        }
    }

    public function detachGroup($group) {
        if (!($group instanceof PermissionGroup) && ctype_digit($group)) {
            $group = PermissionGroup::find($group);
        }

        $this->permissionGroups()->detach($group);

        foreach ($group->permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    public function permissionsArray()
    {
        return CacheHelper::get($this);
    }
}
