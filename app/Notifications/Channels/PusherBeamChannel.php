<?php
 
namespace App\Notifications\Channels;
 
use Illuminate\Notifications\Notification;
 
class PusherBeamChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
 
        $data = $notification->toBeam($notifiable);

     
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => env('PUSHER_BEAMS_INSTANCE_ID'),
            "secretKey" => env('PUSHER_BEAMS_SECRET_KEY'),
          ));
          
        //   $publishResponse = $beamsClient->publishToInterests(
        //     array($data['interest']),
         
        //     array($data['platform'] => array("notification" => array(
        //       "title" => $data['title'],
        //       "body" => $data['body'],
        //       'icon' => $data['icon'],
        //     ) ,
        //     ),
            
        //   ),
        //   array("data" => array(
          
        //     'meta' => $data['id'],
        //   )),
        
        
        // );


        $publishResponse = $beamsClient->publishToInterests(
          [$data['interest']],
          [
            $data['platform'] => [
              "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],
                "icon" => $data['icon'],
              ],
              "data" => $data['data']
            ],
          ]
        );
          
    }
}