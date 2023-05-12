<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;


class OrderItem extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = [];

    protected $casts = [
        'additional' => 'json',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
