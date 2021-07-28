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
  <?php 	include('./public/header.php'); ?>
    <title><?php
	 if (isset($_GET['room'])) { 
		 // verify that room exists
		if (file_exists('./data/messages/'.cleanFilename($_GET['room']).'/webchat.txt')) {
			echo htmlspecialchars($_GET['room']);
		} else {
			echo 'Room not found';
		}
	 } else {
		 echo 'Choose a room';
	 } ?></title>
	<?php
	include('./styles/inject.php');
	echo '<link rel="stylesheet" href="styles/other/editor.css" />';
	$GLOBALS['room'] = $_GET['room'];
	if ($room == "") {
		echo 'You haven\'t specified a room. Here are the available options. If you can\'t find a good room, <a href="create.php">create one.</a>';
		include('libraries/listroom.php');
		include('public/footer.php');
		echo '<!--';
		exit(0);
	}
	if (!file_exists('data/messages/'.cleanFilename($GLOBALS['room']).'/webchat.txt')) {
		echo "No such room, <a href=\"create.php\">create one?</a>";
		include('public/footer.php');
		exit(0);
	}
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  </head>
  <body>
<script>window.scrollTo(0,document.body.scrollHeight);
function update() {
    const exist = globalThis.currentValue;
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "data/messages/<?php echo cleanFilename($GLOBALS['room']); ?>/webchat.txt");
    xhr.send();
    xhr.onload = function () {
        if (xhr.status != 200) {
            document.getElementById("status").innerHTML = `Error ${xhr.status}:${xhr.statusText}`;
        } else {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                globalThis.bottom = true;
            } else {
                globalThis.bottom = false;
            }
            document.getElementById("status").innerHTML = `<strong>Finished refresh</strong> Done,got ${xhr.response.length} bytes`;
            var text = xhr.response;
            if (text !== exist) {
                document.getElementById("message").innerHTML = text;
				globalThis.currentValue = text;
            } else {
                document.getElementById("status").innerHTML = "The same.";
            }
            if (window.bottom) {
                window.scrollTo(0, document.body.scrollHeight);
            }
        }
    };
    xhr.onprogress = function (event) {
        if (event.lengthComputable) {
            document.getElementById("status").innerHTML = `Received ${event.loaded}of ${event.total}bytes`;
        } else {
            document.getElementById("status").innerHTML = `Received ${event.loaded}bytes`;
        }
    };
    xhr.onerror = function () {
        document.getElementById("status").innerHTML = "Request failed";
    };
}
function post() {
    if (document.getElementById("messages").value) {
        document.getElementById("status").innerHTML = "Sending message. Hold tight...";
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "post_message_to_chat.php");
        var data = new FormData(document.getElementById("compose"));
        xhr.send(data);
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("messages").value = "";
                document.getElementById("status").innerHTML = "Sent!";
                update();
            }
        };
    } else {
        document.getElementById("status").innerHTML = '<span style="color:red;"><strong>Your post has no text, try adding some.</strong></span>';
    }
}
var refresh = setInterval(update, 1000);
function insertAtCursor(myField, myValue) {
    if (document.selection) {
        myField.focus();
        var sel = document.selection.createRange();
        sel.text = myValue;
    } else if (myField.selectionStart || myField.selectionStart == "0") {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}
function preview() {
	document.getElementById("status").innerHTML = "Previewing message. Hold tight...";
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "libraries/parsemd.php");
	var data = new FormData(document.getElementById("compose"));
	xhr.send(data);
	xhr.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById('messages').style.display = 'none';
			document.getElementById('previewHTML').innerHTML = xhr.responseText + '<br /><button type="button" onclick="document.getElementById(\'previewHTML\').style.display = \'none\'; document.getElementById(\'messages\').style.display = \'inline-block\';">hide preview</button>';
			document.getElementById('previewHTML').style.display = 'block';
			document.getElementById("status").innerHTML = "Preview finished rendering!";
			update();
		}
	};
}
var enterPressed = function (event) {
    var x = document.querySelector("input[type=radio]:checked");
    if (event.keyCode == 13 && !event.shiftKey && x == document.getElementById("quick")) {
        post();
        event.preventDefault();
        return false;
    }
    if (x == document.getElementById("quick")) {
        if (event.keyCode == 13 && !event.shiftKey) {
            post();
            event.preventDefault();
            return false;
        }
    }
};

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
	<label for="messages">message (markdown allowed, no spam please), <button type="button" onclick="preview()">preview</button></label><textarea rows="7" <?php if ($_COOKIE['login']==''){echo 'disabled="disabled"'; }?> onkeydown="enterPressed(event)" name="message" id="messages" style="width:100%;" required="required" placeholder="Type here"></textarea><br>
	<div id="previewHTML" style="display:none;">Click 'preview'!</div>
	<label for="attach">Attachment filename (<button onclick="window.open('files/', 'upload', 'width=600,height=600');" type="button">upload</button>)</label>
	<input type="text" id="attach" name="attach" placeholder="sample.txt" />
	<input type="hidden" name="room" value="<?php echo $GLOBALS['room']; ?>" /><br>
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
