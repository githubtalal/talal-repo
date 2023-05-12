let permission = Notification.permission;
if (permission === "granted") {
   
} else if (permission === "default") {
    requestAndShowPermission();
} else {
   
}


function requestAndShowPermission() {
    Notification.requestPermission(function (permission) {
        if (permission === "granted") {
            
        }
    });
}
