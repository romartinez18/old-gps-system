<script src="https://cdn.socket.io/socket.io-1.0.0.js"></script>
<script>
  var socket = io.connect('http://localhost:9000/');
  socket.on('news', function (data) {
    console.log(data);
    socket.emit('my other event', { my: 'data' });
  });
</script>