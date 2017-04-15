<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use YaroslavMolchan\Rbac\Models\PermissionGroup;
use YaroslavMolchan\Rbac\Models\Role;

class Permission extends Model
{
    protected $fillable = ['slug', 'name'];

    public function roles() {
    	return $this->belongsToMany(Role::class);
    }

    public function groups() {
    	return $this->belongsToMany(PermissionGroup::class);
    }

    /**
     * @param int|PermissionGroup $group
     */
    public function attachGroup($group) {
        if (!($group instanceof PermissionGroup) && ctype_digit($group)) {
            $group = PermissionGroup::find($group);
        }

        $this->groups()->attach($group);

        foreach ($group->roles as $role) {
            /** @var Role $role */
            try {
                $role->attachPermission($this);
            }
            catch (QueryException $e) {
                //Catch "UNIQUE constraint failed" exceptions, may be groups with the same permissions
                //And when it happens we get error, but now we skip it and continue working
            }
        }
    }

    /**
     * @param int|PermissionGroup $group
     */
    public function detachGroup($group) {
        if (!($group instanceof PermissionGroup) && ctype_digit($group)) {
            $group = PermissionGroup::find($group);
        }

        $this->groups()->detach($group);

        foreach ($group->roles as $role) {
            /** @var Role $role */
            $role->detachPermission($this);
        }
    }
}
