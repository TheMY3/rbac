<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use YaroslavMolchan\Rbac\Models\Permission;
use YaroslavMolchan\Rbac\Models\PermissionsGroup;

class Role extends Model
{
    protected $fillable = ['slug', 'name'];

    public function users() {
    	return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function permissions() {
    	return $this->belongsToMany(Permission::class);
    }

    public function permissionsGroups() {
    	return $this->belongsToMany(PermissionsGroup::class);
    }
}
