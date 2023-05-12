<?php

namespace App\Traits;

trait StoreAccess
{
    public static function bootStoreAccess()
    {
        static::addGlobalScope('store_access', function ($query) {
            if (auth()->user() && auth()->user()->isStoreAdmin()) {
                return $query->where('store_id', auth()->user()->store_id);
            }
            return $query;
        });

        static::saving(function ($model) {
            if (auth()->user() && auth()->user()->isStoreAdmin()) {
                $model->store_id = auth()->user()->store_id;
            }
        });
    }
}
