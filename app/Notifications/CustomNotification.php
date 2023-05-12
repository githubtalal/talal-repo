<?php

namespace App\Notifications;

use App\Notifications\Channels\PusherBeamChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification
{
    use Queueable;

    private $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [PusherBeamChannel::class,'database'];
    }


    public function toBeam($notifiable)
    {
        return [
            'interest' => 'App.User.'.$notifiable->id,
            'platform' => 'web',
            'title' => 'eCart - أي كارت',
            'body' => $this->data['text'],
            'icon' =>  asset('Baseet/images/EcartLogo.svg'),
            'data' => ['id' => $this->id],
        ];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

            'text' => $this->data['text'],
            'url' => $this->data['url'],
            'icon' => asset('Baseet/images/EcartLogo.svg'),
        ];
    }
}
