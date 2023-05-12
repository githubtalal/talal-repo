<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Acl
{
    private $items = [];

    public static function create(): self
    {
        $instance = new self();

        $instance->items = config('acl');

        return $instance;
    }

    public function userHasPermission($user_id, $permission)
    {
        return DB::table('user_permissions')
            ->where('user_id', $user_id)
            ->where('permission_name', $permission)
            ->exists();
    }

    public function storeHasPermission($store_id, $permission)
    {
        $storePermission =  DB::table('store_permissions')->where([
            'store_id' => $store_id,
            'permission_name' => $permission,
        ])->first();

        if ($storePermission) {
            if (is_int($storePermission->max_allowed))
                return $storePermission->remaining > 0;
            else
                return true;
        }

        return false;
    }

    public function storeCanAccess($store_id)
    {
        return DB::table('store_plan')->where([
            'store_id' => $store_id,
        ])->whereDate('expiry_at', '>', now())
            ->exists();
    }

    public function toArray()
    {
        $_items = $this->items;
        $items = [];
        foreach ($_items as $key => $item) {
            $items[$key] = [
                'name' => __($item['name']),
                'key' => $item['key'],
                'route' => $item['main_route'],
                'permissions' => [],
            ];
            foreach ($item['permissions'] as $permission) {
                $items[$key]['permissions'][] = [
                    'name' => __($permission['name']),
                    'route' => $item['key'] . '.' . $permission['route'],
                ];
            }
        }
        return $items;
    }
}
