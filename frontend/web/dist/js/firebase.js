// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
// const firebaseConfig = {
//   apiKey: "AIzaSyBPBtjsO9OnnZF_sgkSE-ia4pcgsYfqpxg",
//   authDomain: "final-project-fgw-383810.firebaseapp.com",
//   projectId: "final-project-fgw-383810",
//   storageBucket: "final-project-fgw-383810.appspot.com",
//   messagingSenderId: "559226681393",
//   appId: "1:559226681393:web:e2b32692b44929ee5c7d10",
//   measurementId: "G-9E0D47M8K7",
// };

var config = {
  messagingSenderId: "559226681393",
  apiKey: "AIzaSyBPBtjsO9OnnZF_sgkSE-ia4pcgsYfqpxg",
  projectId: "final-project-fgw-383810",
  appId: "1:559226681393:web:e2b32692b44929ee5c7d10",
};
firebase.initializeApp(config);
const messaging = firebase.messaging();
messaging
  .requestPermission()
  .then(function () {
    // get the token in the form of promise
    return messaging.getToken();
  })
  .then(function (token) {
    $.ajax({
      type: "POST",
      url: "/site/register-firebase",
      data: {
        token: token,
        deviceType: platform.name,
      },
      success: function (res) {
        
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("Error: " + errorThrown);
      },
    });
  })
  .catch(function (err) {
    console.log("Unable to get permission to notify.", err);
  });

  messaging.onMessage(function(payload) {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body, 
        icon: payload.notification.icon,        
    };
    // console.log(notificationTitle,notificationOptions)

    if (!("Notification" in window)) {
        console.log("This browser does not support system notifications.");
    } else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification(notificationTitle,notificationOptions);
        notification.onclick = function(event) {
            event.preventDefault();
            window.open(payload.notification.click_action , '_blank');
            notification.close();
        }
    }
});

