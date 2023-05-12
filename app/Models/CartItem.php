<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class CartItem extends Model
{
  use HasFactory, SoftDeletes, Loggable;
  protected $guarded = [];

  protected $casts = [
    'additional' => 'array'
  ];

  public function product()
  {
      // withoutGlobalScope is necessary in Re-subscription process,
      // because the product of the cart item is an eCart Product and the store_id is the Store itself.
      return $this->belongsTo(Product::class)->withoutGlobalScope('store_access');
  }
}
