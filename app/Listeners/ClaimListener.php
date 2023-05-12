<?php

namespace App\Listeners;

use App\Events\ClaimEvent;
use App\Providers\EventServiceProvider;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;


class ClaimListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ClaimEvent $event
     * @return void
     */
    public function handle(ClaimEvent $event)
    {
        if (auth()->check())
            DB::table('logs')->insert([
                'action' => $event->type,
                'user_id' => auth()->user()->id,
                'claim_id' => $event->claim->id,
                'model' => 'claim',
                "created_at" => Carbon::now()
            ]);
    }
}
