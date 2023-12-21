<?php

namespace App\Http\Middleware;

use App\UtilityFunction;
use Closure;

class Language
{
    public function handle($request, Closure $next, $guard = null)
    {
        UtilityFunction::getLocal();
        return $next($request);
    }
}
