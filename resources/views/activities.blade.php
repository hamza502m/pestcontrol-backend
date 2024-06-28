<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script>
 
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
 
    var pusher = new Pusher('6887a38008272bc8ffb6', {
      cluster: 'ap2'
    });
 
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        alert(data.data.name);
        alert(data.data.subscribe_to);
    //   alert(JSON.stringify(data));
      console.log(data);
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
