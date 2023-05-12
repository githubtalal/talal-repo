importScripts("https://js.pusher.com/beams/service-worker.js");


PusherPushNotifications.onNotificationReceived = ({ pushEvent, payload , handleNotification }) => {
    // NOTE: Overriding this method will disable the default notification
    // handling logic offered by Pusher Beams. You MUST display a notification
    // in this callback unless your site is currently in focus
    // https://developers.google.com/web/fundamentals/push-notifications/subscribing-a-user#uservisibleonly_options
  
    // Your custom notification handling logic here 🛠️
    // https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerRegistration/showNotification

    const channel = new BroadcastChannel('sw-messages');
    channel.postMessage(payload);
    
    function handleNotification(payload){

      var n_body = payload.notification.body;
      var n_icon = payload.notification.icon;
      var n_id = payload.data.id;
      

      registration.showNotification("أي كارت - eCart", {
        body: n_body,
        icon: n_icon,
      });

     
    
      addEventListener("notificationclick", (event) => {


        var promise = new Promise(function(resolve) {
          setTimeout(resolve, 3000);
      }).then(function() {
          // return the promise returned by openWindow, just in case.
          // Opening any origin only works in Chrome 43+.
          return clients.openWindow('/dashboard/notifications/show_notification/' + n_id);
      });
  
      // Now wait for the promise to keep the permission alive.
      event.waitUntil(promise);
      
      });

  

    }
    pushEvent.waitUntil(

      handleNotification(payload)
    );

    
    
  };