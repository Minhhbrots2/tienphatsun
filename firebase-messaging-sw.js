importScripts('https://www.gstatic.com/firebasejs/8.7.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.7.1/firebase-messaging.js');
// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
	apiKey: "AIzaSyA5YgKfBAn-a0OIwLF26rS4MJ6j_J2kNnc",
	authDomain: "future-homes-d041a.firebaseapp.com",
	projectId: "future-homes-d041a",
	storageBucket: "future-homes-d041a.appspot.com",
	messagingSenderId: "301903874166",
	appId: "1:301903874166:web:94c8b358cab99a0423b757",
	measurementId: "G-75T1E10CNE"
};
firebase.initializeApp(firebaseConfig);
  // Retrieve Firebase Messaging object.
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    self.addEventListener("message", function(event) {
        //event.source.postMessage("Responding to " + event.data);
        self.clients.matchAll().then(all => all.forEach(client => {
            client.postMessage("Responding to " + event.data);
        }));
    });
    // console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    if(payload.data){
        const notificationTitle = payload.data.title;
        const notificationOptions = {
            body: payload.data.body
        };
        if(payload.data.icon){
            notificationOptions['icon'] = payload.data.icon
        }
        if(payload.data.linkTo){
            notificationOptions['data'] = { url:payload.data.linkTo };
            notificationOptions['actions'] = [{action: "open_url", title: "Read Now"}];
        }
        self.registration.showNotification(notificationTitle, notificationOptions);
        self.addEventListener('notificationclick', function(event) {
            let url = event.notification.data.url;
            event.notification.close(); // Android needs explicit close.
            if(url){
                event.waitUntil(
                    clients.matchAll({type: 'window'}).then( windowClients => {
                        // Check if there is already a window/tab open with the target URL
                        for (var i = 0; i < windowClients.length; i++) {
                            var client = windowClients[i];
                            // If so, just focus it.
                            if (client.url === url && 'focus' in client) {
                                return client.focus();
                            }
                        }
                        // If not, then open the target URL in a new window/tab.
                        if (clients.openWindow) {
                            return clients.openWindow(url);
                        }
                    })
                );
            }
        });
    }
});
