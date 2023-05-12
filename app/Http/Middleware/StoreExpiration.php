<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class StoreExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        if (!$user->store->is_active) {
            return redirect()->route('resubscription.index');
        }

        $services = Product::query()->withoutGlobalScope('store_access')->whereJsonContainsKey('additional->permission_name')->get();

        /// At lease one permission is required to enter the dashboard
        foreach ($services as $service) {
            $permissionName = $service->additional['permission_name'];
            if (storeHasPermission($permissionName, $user->store->id))
                return $next($request);
        }

        return redirect()->route('resubscription.index');
    }
}
