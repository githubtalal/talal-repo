<?php

namespace App\Http\Middleware;

use App\Acl;
use Closure;
use Illuminate\Http\Request;

class StoreMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $acl = Acl::create();

        if (!$user->isStoreAdmin())
            return $next($request);
        if (!$acl->storeCanAccess($user->store_id))
            abort('403', 'Store is not active');
        if (!$acl->storeHasPermission($user->store_id, $request->route()->getName()))
            abort('403', 'Store is not authorized to access this page.');
        if (!$acl->userHasPermission($user->id, $request->route()->getName()))
            abort('403', 'You are not authorized to access this page.');

        return $next($request);
    }
}
