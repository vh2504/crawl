<!DOCTYPE html>
<html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script src="js/app.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('88d12ae2283a9490b0ef', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('chat');
    // channel.bind('pusher:subscription_succeeded', function(members) {
    //     alert('successfully subscribed!');
    // });

    channel.bind('test', function(data) {
      alert(JSON.stringify(data));
    });
  

    Echo.private('chat.${this.message.id}')
      .listen('NewEvent', (e) => {
        alert(e.message.content);
        // alert('hello world');        
        console.log(e);
        // console.log('vn');
      });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>

</html>
