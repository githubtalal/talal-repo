<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class PromotionMessage extends Model
{
    use HasFactory, Loggable;

    protected $table = 'promotion_messages';


    protected $fillable = [
        'channel_id',
        'message_text',
        'image',
        'store_id',
        'store_bot_id',
    ];


    public function store_bot()
    {

        return $this->belongsTo(StoreBot::class, 'store_bot_id');
    }


    public function store()
    {

        return $this->belongsTo(Store::class, 'store_id');
    }
}
