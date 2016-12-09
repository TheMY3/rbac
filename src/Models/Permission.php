<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use YaroslavMolchan\Rbac\Models\PermissionsGroup;
use YaroslavMolchan\Rbac\Models\Role;

class Permission extends Model
{
    protected $fillable = ['slug', 'name'];

    public function roles() {
    	return $this->belongsToMany(Role::class);
    }

    public function groups() {
    	return $this->belongsToMany(PermissionsGroup::class);
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
    public function removeRole($role)
    {
        $this->roles()->detach($role);
    }

    /**
     * @author MY
     * @param int|PermissionsGroup $group
     */
    public function addGroup($group) {
        $this->groups()->attach($group);
    }

    /**
     * @author MY
     * @param int|PermissionsGroup $group
     */
    public function removeGroup($group) {
        $this->groups()->detach($group);
    }
}
