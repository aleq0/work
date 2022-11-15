<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JWTAuth
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     *
     *
     * @param $request
     * @param Closure $next
     * @param ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $authenticated = false;

        //check if user is authenticated
        if ($this->auth->guard()->check()) {

            //if guard (role) is provided check if user has that role
            if (!empty($guard)) {
                if (auth()->user()->hasRole($guards[0])) {
                    $authenticated = true;
                }
            }else {
                $authenticated = true;
            }
        }

        if (!$authenticated) {
            return response()->json(['status' => false, 'message' => 'Unauthenticated']);
        }

        return $next($request);
    }
}
