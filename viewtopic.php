<?php
/*
    Forum Software
    Copyright (C) 2022 contributors

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
?><html lang="en">
  <head>
  <?php 	include_once('./public/header.php'); ?>
    <title><?php
	 if (isset($_GET['room'])) { 
		 // verify that room exists
		if (file_exists('./data/messages/'.cleanFilename($_GET['room']).'/config.json')) {
			echo htmlspecialchars($_GET['room']);
		} else {
			echo 'Room not found';
		}
	 } else {
		 echo 'Choose a room';
	 } ?></title>
	<?php
	include_once('./styles/inject.php');
	echo '<link rel="stylesheet" href="styles/other/editor.css" />';
	$GLOBALS['room'] = isset($_GET['room']) ? $_GET['room'] : "";
	if ($room == "") {
		echo 'You haven\'t specified a room. Here are the available options. If you can\'t find a good room, <a href="create.php">create one.</a>';
		include_once('libraries/listroom.php');
		include_once('public/footer.php');
		echo '<!--';
		exit(0);
	}
	if (!file_exists('data/messages/'.cleanFilename($GLOBALS['room']).'/config.json')) {
		echo "No such room, <a href=\"create.php\">create one?</a>";
		include_once('public/footer.php');
		exit(0);
	}
	?>
	  <div id="offlinemsg">You are offline.</div>
	  <label hidden="hidden">DO NOT TOUCH!
	  <textarea id="room" hidden="hidden"><?php echo htmlspecialchars($_GET['room']); ?></textarea></label>
	  <script>var RoomName = document.getElementById('room').value;</script>

	<script>window.addEventListener('load', function(e) {
  if (!navigator.onLine) {
    document.getElementById('offlinemsg').style.display = 'block';
  } else {
    document.getElementById('offlinemsg').style.display = 'none';
  }
}, false);

window.addEventListener('online', function(e) {
  document.getElementById('offlinemsg').style.display = 'none';
}, false);

window.addEventListener('offline', function(e) {
  document.getElementById('offlinemsg').style.display = 'block';
}, false);</script>
  </head>
  <body>
  <h2><?php echo htmlspecialchars($_GET['room']); ?></h2>
  <?php 
	$obj = json_decode(file_get_contents(__DIR__.'/data/messages/'.cleanFilename($GLOBALS['room']).'/config.json'));

  if (file_exists(__DIR__.'/data/messages/'.cleanFilename($GLOBALS['room']).'/del.json')) {
	  $del = json_decode(file_get_contents(__DIR__.'/data/messages/'.cleanFilename($GLOBALS['room']).'/del.json'));
		    ?><div style="color:black;background-color:#ffdddd;"><p>This room has been deleted<?php if (!isset($del->user)) {
				?> automatically because it has accumulated enough flags.<?php
			} ?>.</p><p>Rooms may be removed for reasons of moderation: spam, abuse, etc.</p><p>If you see the topic, either you are an administrator or you are viewing your own deleted topic.</p><p>More deletion information:</p>
		<dl>
			<dt>Deleted:</dt>
			<dd><?php echo friendlyDate($del->time); ?></dd>
			<?php if (isset($del->user)) { ?><dt>Deleter:</dt>
			<dd><?php echo htmlspecialchars($del->user); ?></dd><?php } ?>
			<dt>Reason:</dt>
			<dd><?php $Parsedown = new Parsedown; echo $Parsedown->line($del->reason); ?></dd>
			<?php
			if (isset($del->extendedReason)) {
				if ($del->extendedReason != "") {
					?>
					<dt>More details:</dt>
					<dd>
						<?php echo htmlspecialchars($del->extendedReason); ?>
					</dd><?php
				}
			} ?>
		</dl>
		</div><?php
		if (!verifyAdmin() && $obj->author !== getname()) {
			?><h3>Try:</h3><ul><li>Searching for similar topics</li><li>Asking someone for a screenshot</li><li>Asking an administrator to undelete it</li><?php if (isset($del->user)) { ?><li>If you think this page was deleted in error, private message <?php echo htmlspecialchars($del->user); ?> on the forum.</li><?php } ?></ul><?php
			exit(0);
		}
  }
	$obj->views = intval($obj->views) + 1;
	$file = fopen(__DIR__.'/data/messages/'.cleanFilename($GLOBALS['room']).'/config.json', 'w+');
	fwrite($file, json_encode($obj)) or die('fail');
	fclose($file);
	?>
  <style>#flex-gallery{display:flex;}#flex-gallery>p{margin:3px;}#offlinemsg{display:none;background-color:pink;}</style>
  <div id="flex-gallery">
  <p><strong>Views</strong> <?php echo $obj->views; ?></p>
  <p><strong>Created</strong> <?php echo date('Y-m-d H:i:s', $obj->creationTime);?></p>
  <p><strong>Author</strong> <a href="account/viewuser.php?user=<?php echo htmlspecialchars(urlencode($obj->author)); ?>"><?php echo htmlspecialchars($obj->author); ?></a></p>
  </div>
  <hr />
<script>window.scrollTo(0,document.body.scrollHeight);
function update() {
    const exist = globalThis.currentValue;
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "data/getmsg.php");
	var data = new FormData();
	data.append("room", RoomName);
	xhr.send(data);
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
				hljs.highlightAll();
				if (window.bottom) {
					window.scrollTo(window.scrollY, document.body.scrollHeight);
				}
            } else {
                document.getElementById("status").innerHTML = "No new messages found";
            }
        }
    };
    xhr.onprogress = function (event) {
        if (event.lengthComputable) {
            document.getElementById("status").innerHTML = `Received ${event.loaded} of ${event.total}bytes`;
        } else {
            document.getElementById("status").innerHTML = `Received ${event.loaded} bytes`;
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
            } else if (this.readyState == 4 && this.status != 200) {
				document.getElementById('overlay').style.display = 'block';
				document.getElementById('error').innerHTML = 'XHR Ready State: '+this.readyState+"\nStatus: "+this.status;
			}
        };
    } else {
        document.getElementById("status").innerHTML = '<span style="color:red;"><strong>Your post has no text, try adding some.</strong></span>';
    }
}
var refresh = setInterval(update, 10000);
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
			hljs.highlightAll();
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

</script><div id="message" style="font-family:inherit;"><?php 
include_once('data/getmsg.php');
getMsg($_GET['room']);
?></div>	
	  <?php blockCheck(); ?>
	  <details style="position:sticky; bottom:0; background-color:lightblue; overflow-y:scroll;max-height:calc(100% - 7em); color: black;">
	<summary style="list-style: none;padding-left:10;">-<span></span>-> Reply</summary>
	<span id="status">loading status</span><br />
	<form action="javascript:void(0);" id="compose" onsubmit="post();">
	<div id="load-js" hidden="hidden"><span><input checked="checked" type="radio" name="mode" id="quick" />
	<label for="quick">Quick chat (enter = send)</label></span>
	<span><input type="radio" name="mode" id="nonquick" />
	<label for="nonquick">Detailed chat (click send = send)</label></span>
	<br />
	<label for="messages">message (markdown allowed, no spam please), <button type="button" onclick="preview()">preview</button></label><textarea rows="7" <?php if ($_COOKIE['login']==''){echo 'disabled="disabled"'; }?> onkeydown="enterPressed(event)" name="message" id="messages" style="width:100%;" required="required" placeholder="Type here"></textarea><br>
	<div id="previewHTML" style="display:none;">Click 'preview'!</div>
		<details><summary>How to reply to a question</summary>
		<ol>
			<li>All replies, even those by trusted users, go through a review system, which can be accessed by any user (including you!) <a href="review/">here</a>. In review, good posts are allowed to pass while low-quality posts are flagged for further review by administrators. Before someone reviews your post, the post will still be visible to everyone. (Reviewing your own post is not allowed.)</li>
			<li>Make sure your post is a legitimate attempt at responding to the question. Replies stating that someone has the same problem and follow up questions are not permitted.</li>
			<li><strong>No matter how late you are, feel free to submit a response anyway. Even if the original person got an answer, others might appreciate another solution.</strong></li>
			<li>Make sure your post is readable. You can press the "preview" button to see how it will look.</li>
			<li>After posting, please do not refresh the page. The post could take up to 10 seconds to appear.</li>
			<li>New replies will appear live on the bottom.</li>
		</ol></details>
	<label for="attach">Attachment filename (<a href="files/directory.php" target="_blank">Use or upload files</a>)</label>

	<input type="text" id="attach" name="attach" placeholder="sample.txt" /><br />
	<label>Reply to: <input type="text" name="reply" placeholder="admin" /></label>
	<input type="hidden" name="room" value="<?php echo htmlspecialchars($GLOBALS['room']); ?>" /><br />
	<input type="hidden" name="js" value="a" />
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
	</div></form>
	<noscript>JavaScript is disabled. Please use the <a href="reply.php?room=<?php echo htmlspecialchars(urlencode($_GET['room'])); ?>">reply form</a> instead.</noscript>
	</details>
	<script>
update();
document.querySelector("#load-js").style.display = 'block';
</script>
<div class="blanket" id="overlay" style="display: none;width:100vw;height:100vw;">
	<div class="overlay" style="display:block;">
		<h2>A problem occurred while sending your request</h2>
		Please try again later.
		<br />
		<pre>Error code: <br /><code id="error"></code></pre>
		<br />
		<input type="button" onclick="this.parentNode.parentNode.style.display = 'none';" value="close" />
	</div>
</div>
<?php include_once('./public/footer.php'); ?>