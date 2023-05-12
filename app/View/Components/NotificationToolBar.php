<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class NotificationToolBar extends Component
{
    public $notifications;

    public $unread_notifications_count;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user_id = auth()->id();

        $this->notifications = DB::table('notifications')->where(['notifiable_id' => $user_id,'read_at'=> null])->latest()->get();
        $this->unread_notifications_count = DB::table('notifications')->where(['notifiable_id' => $user_id,'read_at'=> null])->count();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.notification-tool-bar');
    }
}
