<html>
<title>Firebase Messaging Demo</title>
<style>
    div {
        margin-bottom: 15px;
    }
</style>
<body>
    <div id="token"></div>
    <div id="msg"></div>
    <div id="notis"></div>
    <div id="err"></div>

    <form method="post" action="send-message">
        {{csrf_field()}}
        <input type="text" name="target" id="target" value="" hidden=true>
        <button type="submit">Send fcm message</button>
    </form>


    <script src="https://www.gstatic.com/firebasejs/7.2.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.2.1/firebase-messaging.js"></script>
    <!-- For an optimal experience using Cloud Messaging, also add the Firebase SDK for Analytics. -->
    <script src="https://www.gstatic.com/firebasejs/7.2.1/firebase-analytics.js"></script>
    <script>
        MsgElem = document.getElementById("msg");
        TokenElem = document.getElementById("token");
        NotisElem = document.getElementById("notis");
        ErrElem = document.getElementById("err");
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
       
        var config = {
            apiKey: "AIzaSyAJzbGlZr0O3oY850lGf_DgHl1ovXrDBJg",
            authDomain: "web-push-notification-e2811.firebaseapp.com",
            databaseURL: "https://web-push-notification-e2811.firebaseio.com",
            projectId: "web-push-notification-e2811",
            storageBucket: "web-push-notification-e2811.appspot.com",
            messagingSenderId: "256366540112",
            appId: "1:256366540112:web:1ebbcee5fa234fa91a6855",
            measurementId: "G-Y7Y31ZYLPK"
        };
        firebase.initializeApp(config);

        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                MsgElem.innerHTML = "Notification permission granted." 
                console.log("Notification permission granted.");
                
                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                TokenElem.innerHTML = "token is : " + token
                document.getElementById("target").value = token;
            })
            .catch(function (err) {
                ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                console.log("Unable to get permission to notify.", err);
            });

        messaging.onMessage(function(payload) {
            console.log("Message received. ", payload);
            NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload);
            //kenng - foreground notifications
            const {title, ...options} = payload.notification;
            navigator.serviceWorker.ready.then(registration => {
                registration.showNotification(title, options);
            });
        });
    </script>

    </body>

</html>