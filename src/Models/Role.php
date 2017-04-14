<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use YaroslavMolchan\Rbac\Models\Permission;
use YaroslavMolchan\Rbac\Models\PermissionsGroup;

class Role extends Model
{
    protected $fillable = ['slug', 'name'];

    private function getCacheKey() {
        return 'role_'.$this->slug;
    }

    public function users() {
    	return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function permissions() {
    	return $this->belongsToMany(Permission::class);
    }

    public function permissionsGroups() {
    	return $this->belongsToMany(PermissionsGroup::class);
    }

    public function givePermissionTo($permission) {
        $this->permissions()->attach($permission);
        \Cache::forget($this->getCacheKey());
    }

    public function takePermissionFrom($permission) {
        $this->permissions()->detach($permission);
        \Cache::forget($this->getCacheKey());
    }

    public function givePermissionGroupTo(PermissionsGroup $group) {
        $this->permissionsGroups()->attach($group);
        foreach ($group->permissions as $permission) {
            $this->givePermissionTo($permission);
        }
    }

    public function takePermissionGroupFrom(PermissionsGroup $group) {
        $this->permissionsGroups()->detach($group);
        foreach ($group->permissions as $permission) {
            $this->takePermissionFrom($permission);
        }
    }

    public function permissionsArray()
    {
        $key = $this->getCacheKey();
        if (false === \Cache::has($key)) {
            $permissions = $this->permissions()->pluck('slug')->toArray();
            \Cache::forever($key, $permissions);
        }

        return \Cache::get($key);
    }
}
