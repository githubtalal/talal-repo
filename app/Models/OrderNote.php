<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class OrderNote extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
