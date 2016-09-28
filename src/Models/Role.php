<?php

namespace YaroslavMolchan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'label'];

    public function users() {
    	return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function permissions() {
    	return $this->belongsToMany(Permission::class);
    }
}
