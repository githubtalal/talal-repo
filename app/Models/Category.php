<?php

namespace App\Models;

use App\Traits\StoreAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;


class Category extends Model
{
    use HasFactory, StoreAccess, SoftDeletes, Loggable;
    protected $fillable = [
        'name',
        'store_id',
        'image',
        'image_settings',
        'active'
    ];
    protected $casts = [
        'image_settings' => 'json',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
