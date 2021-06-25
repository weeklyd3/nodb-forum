<html>
  <head>
    <title>Forums &mdash; Web Chat</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  </head>
  <body><script>window.scrollTo(0,document.body.scrollHeight);
function update() {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open("GET", "data/messages/webchat.txt");

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = function () {
        if (xhr.status != 200) {
            // analyze HTTP status of the response
            document.getElementById('status').innerHTML=(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        } else {
var pos = [window.scrollX, window.scrollY];
			console.log(pos);
window.onscroll = function(ev) {
  if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
    console.log("bottom of the page reached");
	globalThis.bottom = true;
  }
};
            // show the result
            document.getElementById('status').innerHTML=(`Done, got ${xhr.response.length} bytes`); // response is the server response
			var text = xhr.responseText;
			document.getElementById('message').innerHTML='';
			document.getElementById('message').innerHTML=text;
			window.scrollTo(0, 0);
			window.scrollTo(pos[0], pos[1]);
			if (window.bottom) {
				window.scrollTo(0,document.body.scrollHeight);
			} else {
				window.scrollTo(0, 0);
				window.scrollTo(pos[0], pos[1]);
			}
        }
    };

    xhr.onprogress = function (event) {
        if (event.lengthComputable) {
            document.getElementById('status').innerHTML=(`Received ${event.loaded} of ${event.total} bytes`);
        } else {
            document.getElementById('status').innerHTML=(`Received ${event.loaded} bytes`); // no Content-Length
        }
    };

    xhr.onerror = function () {
        document.getElementById('status').innerHTML=("Request failed");
    };
}
function post() {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open("POST", "post_message_to_chat.php");
	var data = new FormData(document.getElementById('compose'));
    // 3. Send the request over the network
    xhr.send(data);

    // 4. This will be called after the response is received
	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById('messages').value="";
			console.log('sent');
		}
	};
}
setInterval(update, 1000);
</script><pre id="message"><h2>This is the start of chat.</h2></pre>
	<details style="position:sticky; bottom:0; background-color:lightblue;" open="open">
	<summary>Compose message</summary>
	<p id="status">loading status</p>
	<form action="javascript:void(0);" id="compose" onsubmit="post();">
	<label for="messages">message</label><br>
	<input type="text" <?php if ($_COOKIE['login']==''){echo 'disabled="disabled"'; }?> name="message" id="messages" style="width:100%;" required="required" /><br>
	<?php
	$name = $_COOKIE['login'];
	if ($name != "") {
		echo '<input type="hidden" name="login" value="'.htmlspecialchars($name).'" />';
		echo '<input type="submit" value="send" />';
	} else {
		echo "You have to log in to post messages.";
	}
	?>
	</form>
	</details>
