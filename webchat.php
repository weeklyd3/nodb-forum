<?php
/*
    Forum Software
    Copyright (C) 2021 contributors

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
?><html>
  <head>
  
    <title>Forums &mdash; Web Chat</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	echo '<link rel="stylesheet" href="styles/other/editor.css" />';
	$GLOBALS['room'] = $_GET['room'];
	if ($room == "") {
		echo 'You haven\'t specified a room. Here are the available options. If you can\'t find a good room, <a href="create.php">create one.</a>';
		include('libraries/listroom.php');
		echo '<!--';
	}
	if (!file_exists('data/messages/'.cleanFilename($GLOBALS['room']).'/webchat.txt')) {
		echo "No such room, <a href=\"create.php\">create one?</a><!--";
		include('public/footer.php');
	}
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  </head>
  <body>
<script>window.scrollTo(0,document.body.scrollHeight);
function update() {
	var exist = document.getElementById('message').innerHTML;
    let xhr = new XMLHttpRequest();

    xhr.open("GET", "data/messages/<?php echo cleanFilename($GLOBALS['room']); ?>/webchat.txt");

    xhr.send();

    xhr.onload = function () {
        if (xhr.status != 200) {
            document.getElementById('status').innerHTML=(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        } else {
			var pos = [window.scrollX, window.scrollY];
			if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
				globalThis.bottom = true;
			} else {
				globalThis.bottom = false;
			}
            document.getElementById('status').innerHTML=(`<strong>Finished refresh</strong> Done, got ${xhr.response.length} bytes`);
			var text = xhr.responseText;
			document.getElementById('message').innerHTML = text;
			window.scrollTo(0, 0);
			window.scrollTo(pos[0], pos[1]);
			if (window.bottom) {
				window.scrollTo(0,document.body.scrollHeight);
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
	if (document.getElementById('messages').textContent) {
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
				document.getElementById('messages').innerHTML="";
				document.getElementById('status').innerHTML = 'Sent!'
				update();
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
enterPressed = function(event) {
	var x = document.querySelector('input[type=radio]:checked');
	if (event.keyCode == 13 && !event.shiftKey && x == document.getElementById('quick')) {
		post(); 
		event.preventDefault();
		return false; 
	}
	if (x == document.getElementById('quick')) {
		if (event.keyCode == 13 && !event.shiftKey) {
			post(); 
			event.preventDefault();
			return false; 
		}
	}
}
</script><div id="message" style="font-family:inherit;"><?php echo file_get_contents('data/messages/'.cleanFilename($GLOBALS['room']).'/webchat.txt'); ?></div>
	<details style="position:sticky; bottom:0; background-color:lightblue; max-height:75%;" open="open">
	<summary style="list-style: none;">Reply</summary>
	<span id="status">loading status</span><br />
	<form action="javascript:void(0);" id="compose" onsubmit="post();">
	<span><input checked="checked" type="radio" name="mode" id="quick" />
	<label for="quick">Quick chat (enter = send)</label></span>
	<span><input type="radio" name="mode" id="nonquick" />
	<label for="nonquick">Detailed chat (click send = send)</label></span>
	<br />
	<label for="messages">message (no spam please)</label><br>
	<div required="required" contenteditable="<?php if ($_COOKIE['login']==''){echo 'false'; }else{echo 'true';}?>" onkeydown="enterPressed(event);document.getElementById('messageText').value=this.innerHTML;" onkeyup="document.getElementById('messageText').value=this.innerHTML" onmousedown="document.getElementById('messageText').value=this.innerHTML" onmouseup="document.getElementById('messageText').value=this.innerHTML" id="messages" style="width:calc(100% - 10);height:7em;margin:5px;border:1px solid;padding:7px;overflow-y:scroll;" placeholder="Type here"></div>
	<textarea required="required" rows="7" <?php if ($_COOKIE['login']==''){echo 'disabled="disabled"'; }?> onkeydown="enterPressed(event)" name="message" id="messageText" style="display:none;width:100%;" required="required" placeholder="Type here"></textarea><input type="hidden" name="room" value="<?php echo $GLOBALS['room']; ?>" /><br>
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
	<script>
update();
</script>