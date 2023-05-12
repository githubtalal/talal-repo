<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class BotMessages extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'additional' => 'json'
    ];
}
