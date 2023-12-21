<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $REQUEST_URI = strtolower($_SERVER['REQUEST_URI']);

        $user = Auth::user();

        if (strpos($REQUEST_URI, '/collector') !== false) {
            if (is_null($user)) {
                return redirect('/');
            } else {
                return $next($request);
            }
        }
        if (strpos($REQUEST_URI, '/admin') !== false) {

            if (is_null($user)) {
                return redirect('/admin/login');
            } else {
                return $next($request);
            }
        }
    }
}
