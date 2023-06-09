<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class StoreSettings extends Model
{
    use HasFactory, Loggable;
    protected $casts = [
        'value' => 'json'
    ];
    protected $guarded = [];
}
