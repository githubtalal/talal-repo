<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use App\Traits\Loggable;


class Store extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];
    protected $casts = [
        'additional_info' => 'json',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function bots()
    {
        return $this->hasMany(StoreBot::class);
    }

    public function promotion_messages()
    {

        return $this->hasMany(PromotionMessage::class, 'store_id');
    }

    public function getFeatureExpiryDate($feature)
    {
        $permission = Permission::where('name', $feature)->first();

        $model_has_permission = \DB::table('model_has_permissions')
            ->where([
                ['model_id', $this->user->first()->id],
                ['permission_id', $permission->id],
            ])->first();


        return ($model_has_permission && $model_has_permission->expires_at) ?
            Carbon::parse($model_has_permission->expires_at)->timezone('Asia/Damascus') :
            null;
    }
}
