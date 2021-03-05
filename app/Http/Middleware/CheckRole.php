<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Route;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uri         = Route::getFacadeRoot()->current()->uri();  // "admin/"
        $url_head    = explode('/', $uri)[0];                    // 'admin'
        $role_routes = Role::distinct()->whereNotNull(
            'allowed_route'
        )->pluck('allowed_route')->toArray();                    // ['admin'] distinct not repeated

        if (auth()->check()) {
            if (!in_array($url_head,$role_routes)) {
                return $next($request);
            } else {
                if($url_head != auth()->user()->roles[0]->allowed_route){
                    // bug here does not redirect & i think this is the bug source 'admin.show_login_form'
                    return redirect_to('admin.show_login_form');
                } else {
                    return $next($request);
                }
            }
        } else {
            // bug here does not redirect & i think this is the bug source 'admin.show_login_form'
            $path = (in_array($url_head,$role_routes))? 'admin.show_login_form': 'frontend.show_login_form';
            return redirect_to($path);
        }
    }
}
