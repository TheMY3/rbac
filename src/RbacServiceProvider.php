<?php

namespace YaroslavMolchan\Rbac;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations/' => base_path('/database/migrations')
        ], 'migrations');

        Blade::directive('ifUserIs', function($expression){
            return "<?php if(Auth::check() && Auth::user()->hasRole({$expression})): ?>";
        });
        Blade::directive('ifUserCan', function($expression){
            return "<?php if(Auth::check() && Auth::user()->canDo({$expression})): ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
