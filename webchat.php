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
			if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
				console.log("bottom of the page reached");
				globalThis.bottom = true;
			} else {
				globalThis.bottom = false;
			}
            // show the result
            document.getElementById('status').innerHTML=(`<strong>Finished refresh</strong> Done, got ${xhr.response.length} bytes`);
			 // response is the server response
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
	if (document.getElementById('messages').value) {
		document.getElementById('status').innerHTML='Sending message. Hold tight...';
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
				document.getElementById('status').innerHTML = 'Sent!'
				console.log('sent');
			}
		};
	} else {
		document.getElementById('status').innerHTML='<span style="color:red;"><strong>Refusing to send because it&apos;s empty.</strong></span>';
	}
}
var refresh = setInterval(update, 1000);
function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}
</script><pre id="message" style="font-family:inherit;"><h2><em>Please wait...</em></h2></pre>
	<details style="position:sticky; bottom:0; background-color:lightblue; max-height:75%;" open="open">
	<summary>Compose message</summary>
	<p id="status">loading status</p>
	<form action="javascript:void(0);" id="compose" onsubmit="post();">
	<label for="messages">message (no spam please)</label><br>
	<textarea required="required" onkeydown="if (event.keyCode == 13 && !event.shiftKey) { post(); return false; }" rows="7" <?php if ($_COOKIE['login']==''){echo 'disabled="disabled"'; }?> name="message" id="messages" style="width:100%;" required="required" placeholder="Type here"></textarea><br>
	<?php
	$name = $_COOKIE['login'];
	if ($name != "") {
		echo '<input type="hidden" name="login" value="'.htmlspecialchars(getname()).'" />';
		echo '<input type="submit" value="send" />';
		echo '&nbsp;&nbsp;<input type="button" value="stop refresh" onclick="clearInterval(refresh);document.getElementById(\'status\').innerHTML=\'Disconnected. Reload to start connection again.\';" />';
	} else {
		echo "You have to log in to post messages.";
	}
	?>
	</form>
	</details>