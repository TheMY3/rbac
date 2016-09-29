<?php
namespace YaroslavMolchan\Rbac\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Rbac
{
    private $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth) 
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $level, $permission)
    {
        if ($this->auth->check() && $this->auth->user()->roleIs($role)) {
            return $next($request);
        }
        
        if(!in_array($level, ['is', 'can']))
            abort(500, 'Invalid RBAC operator specified.');
        if('is' == $level) {
            if($request->user()->hasRole($permission))
                return $next($request);
        } else {
            if($request->user()->canDo($permission))
                return $next($request);
        }
        abort(403);
    }
}