<?php

namespace App\Models;

use App\Traits\StoreAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;


class Order extends Model
{
    use HasFactory, StoreAccess, SoftDeletes, Loggable;

    const PENDING = 'pending';
    const PAID = 'paid';
    const FAILED = 'failed';
    const CANCELED = 'canceled';
    const COMPLETED = 'completed';
    const INPROGRESS = 'in_progress';

    protected $guarded = [];

    protected $casts = [
        'payment_info' => 'json',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function notes()
    {
        return $this->hasMany(OrderNote::class);
    }

    public function statusLabel()
    {
        return __('app.order.statuses.' . $this->status);
    }

    public static function getStatuses()
    {
        return __('app.order.statuses');
    }

    public static function getAllStatus()
    {
        return [
            Order::PENDING,
            Order::PAID,
            Order::FAILED,
            Order::CANCELED,
            Order::COMPLETED,
            Order::INPROGRESS,
        ];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'store_id', 'store_id');
    }

    public function isPaid()
    {
        return in_array($this->status, [self::COMPLETED, self::PAID]);
    }

    public function isCanceled()
    {
        return in_array($this->status, [self::FAILED, self::CANCELED]);
    }

    public function getCanNotEditOrderAttribute()
    {
        $method = config('payment_methods.' . $this->payment_method);
        return (new $method())->needsRedirect();
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }


    public function isSubscription()
    {
        $subscription = Subscription::where('order_id', $this->id)->first();
        return $subscription;
    }
}
