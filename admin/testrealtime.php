<html>
<head>
<title>Socket notification</title>
</head>
<body>

<div id="status">
</div>

<div id="commentBox">
    <input type = "text" id = "name" size = "40" placeholder="Your name - put shahid"><br><br>
    <textarea cols = "38" rows = "10" id = "comment" placeholder="Add your comment"></textarea><br><br>
    <input type = "button" id = "addComment" value = "Comment"><br>
    <span id = "message"></span>
</div>

</body>
<style media="screen">
body {
    padding : 50
}
#status {
    width : 250px;
    padding : 10px;
    font-size : 14px;
    margin-left: 20%;
}
#commentBox {
    width: 250px;
    padding: 10px;
    margin-top : 10px;
    margin-left : 20%;
}
</style>
<script src="js/socket.io-1.4.5.js"></script>
<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>

<script>
$(document).ready(function(){
    var socket = io();
    $.get("/getStatus",function(data){
        if(data.error) {
            $("#message").empty().text(data.message);
        } else {
            $("#status").text(data.message[0].UserPostContent);
        }
    });
    $("#addComment").click(function(event){
        var userName = $("#name").val();
        var userComment = $("#comment").val();
        if(userName === "" || userComment === "") {
            alert("Please fill the form.");
            return;
        }
        socket.emit('comment added',{user : userName, comment : userComment});
        socket.on('notify everyone',function(msg){
            notifyMe(msg.user,msg.comment);
        });
    });
});
function notifyMe(user,message) {
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }
  // Let's check if the user is okay to get some notification
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
  var options = {
        body: message,
        dir : "ltr"
    };
  var notification = new Notification(user + " Posted a comment",options);
  }
  // Otherwise, we need to ask the user for permission
  // Note, Chrome does not implement the permission static property
  // So we have to check for NOT 'denied' instead of 'default'
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      // Whatever the user answers, we make sure we store the information
      if (!('permission' in Notification)) {
        Notification.permission = permission;
      }
      // If the user is okay, let's create a notification
      if (permission === "granted") {
        var options = {
                body: message,
                dir : "ltr"
        };
        var notification = new Notification(user + " Posted a comment",options);
      }
    });
  }
  // At last, if the user already denied any notification, and you
  // want to be respectful there is no need to bother them any more.
}
</script>

</html>