<?php

namespace App\Http\Trait;

use App\Events\ClaimEvent;
use Illuminate\Support\Facades\Event;

trait ClaimTrait
{
    public static function bootClaimTrait()
    {
        self::creating(function ($model) {
            Event::dispatch(new ClaimEvent($model, 'creating'));
        });
        self::created(function ($model) {
            Event::dispatch(new ClaimEvent($model, 'created'));
        });
        self::updating(function ($model) {
            Event::dispatch(new ClaimEvent($model, 'updating'));
        });
        self::updated(function ($model) {
            Event::dispatch(new ClaimEvent($model, 'updated'));
        });
        self::deleting(function ($model) {
            Event::dispatch(new ClaimEvent($model, 'deleting'));
        });
        self::deleted(function ($model) {
            Event::dispatch(new ClaimEvent($model, 'deleted'));
        });
        self::saved(function ($model) {
//            new ClaimEvent($modal, 'saved');
//            Event::dispatch(ClaimEvent::class ,[$modal,'saved']);
            Event::dispatch(new ClaimEvent($model, 'saved'));
        });

    }
}
