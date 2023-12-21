<?php

namespace App\Http\Middleware;

use App\Http\Enum\AdminPermissionEnum;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (User::hasPermission(Auth::user(), AdminPermissionEnum::VIEW_DASHBOARD)) {
            return $next($request);
        } else {
            return redirect('/admin/no-permission');
        }
    }
}
