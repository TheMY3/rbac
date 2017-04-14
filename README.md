# Laravel RBAC
Simple RBAC/ACL for Laravel 5.3 and more with caching and permission groups.

## Installation

Install this package with composer using the following command:

```
composer require yaroslavmolchan/rbac
```

or you can add to your `composer.json`

```
"require": {
    ...
    "yaroslavmolchan/rbac": "^0.9"
}
```

then run `composer update`.

Add Service Provider to `providers` array in `config/app.php` file.

```php
'providers' => [
    ...
    /*
     * Package Service Providers...
     */
    YaroslavMolchan\Rbac\RbacServiceProvider::class,
    ...
],
```

Publish migration files

```
$ php artisan vendor:publish --provider="YaroslavMolchan\Rbac\RbacServiceProvider" --tag=migrations
```

And then run migrations

```
$ php artisan migrate
```

Add middleware to your `app/Http/Kernel.php` file.

```php
protected $routeMiddleware = [
    ...
    'role' => \YaroslavMolchan\Rbac\Middleware\CheckRole::class,
    'permission' => \YaroslavMolchan\Rbac\Middleware\CheckPermission::class
];
```

Add Rbac trait to your `User` model

```php
use \YaroslavMolchan\Rbac\Traits\Rbac;
	
class User extends Authenticatable
{
    use Rbac;
    ...
	    
}
```

## Usage

### Roles

#### Creating roles

```php
use \YaroslavMolchan\Rbac\Models\Role;

$adminRole = Role::create([
    'name' => 'Administrator',
    'slug' => 'admin'
]);

$managerRole = Role::create([
    'name' => 'Manager',
    'slug' => 'manager'
]);
```

#### Attaching And Detaching Roles
	
You can simple attach role to user:
```php
use App\User;

$user = User::find(1);
$user->assingRole($adminRole);
//or you can insert only id
$user->assingRole($adminRole->id);
```
And the same if you want to detach role:
```php
use App\User;

$user = User::find(1);
$user->revokeRole($adminRole);
//or you can insert only id
$user->revokeRole($adminRole->id);
```
### Checking for roles

You can simple check if user has role:
```php
use App\User;

$user = User::find(1);
if ($user->hasRole('admin')) {
    
}
```

### Permissions

#### Creating permissions

```php
use \YaroslavMolchan\Rbac\Models\Permission;

$createPermission = Permission::create([
    'name' => 'Create product',
    'slug' => 'product.create'
]);

$removePermission = Permission::create([
    'name' => 'Delete product',
    'slug' => 'product.remove'
]);
```

#### Attaching And Detaching permissions

You can attach permission to role very simple:
```php
use \YaroslavMolchan\Rbac\Models\Role;

$adminRole = Role::find(1);
$adminRole->givePermissionTo($createPermission);
//or you can insert only id
$adminRole->givePermissionTo($createPermission->id);
```
And the same to detach permission:
```php
use \YaroslavMolchan\Rbac\Models\Role;

$adminRole = Role::find(1);
$adminRole->takePermissionGroupFrom($createPermission);
//or you can insert only id
$adminRole->takePermissionGroupFrom($createPermission->id);
```
### Checking for permissions

You can simple check if user has permission:
```php
use App\User;

$user = User::find(1);
if ($user->canDo('product.create')) {
    
}
```
All permissions for each role store in cache, and when you check for permission - it take information from cache, that`s why its works quickly.

### Permission groups

Permission groups created for group some permissions in one main group, and then you can attach permission group to role and all permissions in this group attach to this role to. Its very useful thing.

#### Creating permission groups

```php
use \YaroslavMolchan\Rbac\Models\PermissionsGroup;

$productManagementPermissionsGroup = PermissionsGroup::create([
    'name' => 'Product management',
    'module' => 'main' // optional
]);
```

#### Add and Remove permissions to group

You can add permission to group very simple:
```php
use \YaroslavMolchan\Rbac\Models\Permission;

$createPermission = Permission::find(1);
$productManagementPermissionsGroup->addPermission($createPermission);
//or you can insert only id
$productManagementPermissionsGroup->addPermission($createPermission->id);
```
And the same to remove permission from group:
```php
use \YaroslavMolchan\Rbac\Models\Permission;

$createPermission = Permission::find(1);
$productManagementPermissionsGroup->removePermission($createPermission);
//or you can insert only id
$productManagementPermissionsGroup->removePermission($createPermission->id);
```

#### Attaching And Detaching permission groups to role

You can attach permission group to role very simple:
```php
use \YaroslavMolchan\Rbac\Models\Role;

$adminRole = Role::find(1);
$adminRole->givePermissionGroupTo($productManagementPermissionsGroup);
```
And the same to detach permission group:
```php
use \YaroslavMolchan\Rbac\Models\Role;

$adminRole = Role::find(1);
$adminRole->takePermissionGroupFrom($productManagementPermissionsGroup);
```

### Protected routes

You can easily protect your routes with `role` and `permission` params:

```php
Route::get('/admin', [
    'uses' => 'AdminController@index',
    'middleware' => 'role:admin'
]);

Route::get('/products/create', [
    'uses' => 'ProductsController@create',
    'middleware' => 'permission:product.create'
]);
```

### Blade Extensions

You can check roles and permissions in Blade like this:

```php
@ifUserIs('admin')
    // show content only for admin
@else
    // show content for other roles
@endif

@ifUserCan('product.create')
    // show product create content
@endif
```

## License

Laravel RBAC is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)