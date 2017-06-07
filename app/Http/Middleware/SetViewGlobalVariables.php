<?php
namespace Brightfox\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class SetViewGlobalVariables
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();
        view()->share('logged_user', $user);

        return $next($request);
    }

}