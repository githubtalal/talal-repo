<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Cart extends Model
{
    use HasFactory,  SoftDeletes, Loggable;

    protected $guarded = [];

    protected $casts = [
        'payment_info' => 'json',
        'additional' => 'json'
    ];
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
