importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts(
  "https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"
);

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
firebase.initializeApp({
  messagingSenderId: "559226681393",
  apiKey: "AIzaSyBPBtjsO9OnnZF_sgkSE-ia4pcgsYfqpxg",
  projectId: "final-project-fgw-383810",
  appId: "1:559226681393:web:e2b32692b44929ee5c7d10",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
  console.log(
    "[firebase-messaging-sw.js] Received background message ",
    payload
  );
  // Customize notification here
  const notificationTitle = "Background Message Title";
  const notificationOptions = {
    body: "Background Message body.",
    icon: "/firebase-logo.png",
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});

messaging.setBackgroundMessageHandler(function(payload) {

    console.log(payload);

    return self.registration.showNotification(title, options);
});
