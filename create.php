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
    <title>Forums &mdash; Create Chat Room</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body>
  <?php 
  if (!$_COOKIE['login']) {
	  echo 'You need to log in to create rooms.';
	  include('./public/footer.php');
	  exit(0);
  }
		if (isset($_POST['roomtitle'])) {
			include('libraries/parsedown.php');
			$parsedown = new Parsedown();
			class Room {
				function __construct($message) {
					echo $message;
				}
				public $title = null;
				public $description = null;
				public $description_html = null;
			}
			$room = new Room("-<span></span>-> constructed template");
			$room->title = $_POST['roomtitle'];
			$room->description = $_POST['description'];
			$room->description_html = $parsedown->text($room->description);
			echo '<h2>';
			echo htmlspecialchars($room->title) . ' is being created...</h2>';
			echo '<pre>'.htmlspecialchars(json_encode($room)).'</pre>';
			if (!file_exists('data/messages/'.cleanFilename($room->title).'/config.json')) {
				$dir = mkdir('data/messages/'.cleanFilename($room->title));
				if ($dir) {
					$filename = 'data/messages/'.cleanFilename($room->title) . '/config.json';
					$handle = fopen($filename, 'w+');
					$one = fwrite($handle, json_encode($room));
					$filename = 'data/messages/'.cleanFilename($room->title) . '/webchat.txt';
					$handle = fopen($filename, 'w+');
					$two = fwrite($handle, $room->description_html);
					if ($one && $two) {
						echo "Created";
					}
				}
			} else {
				echo 'Room already exists';
			}
 		}
	?>
	<form id="form" action="<?php echo $_SERVER['PHP_SELF']; echo '" '; if(isset($_POST['roomtitle'])) { echo 'style="display:none;"'; }?> method="post">
    	<strong>WARNING: Only create new rooms if you absolutely need to!</strong>
		<br />
		<label for="roomtitle">Room title:</label>
		<input type="text" id="roomtitle" name="roomtitle" required="required" />
		<br />
		<div id="toolbar"><button type="button" onclick="document.getElementById('tips').src='files/md.php#bold';"><strong>B</strong></button>
		<button type="button" onclick="document.getElementById('tips').src='files/md.php#italic';"><em>I</em></button>

		<button type="button" onclick="document.getElementById('tips').src='files/md.php#sub';"><span><sub>2</sub></span></button>
		<button type="button" onclick="document.getElementById('tips').src='files/md.php#sup';"><sup>2</sup></button>
		
		<button type="button" onclick="document.getElementById('tips').src='files/md.php#hr';">&mdash;</button>

		<button type="button" onclick="document.getElementById('tips').src='files/md.php#block';"><span style="font-size:0.3rem;font-family:monospace;">ABC<br />DEF</span></button>
		<button type="button" onclick="document.getElementById('tips').src='files/md.php#inlinecode';"><span style="font-family:monospace;">ABC</span></button>

		<button type="button" onclick="document.getElementById('tips').src='files/md.php#link';">Link</button>

		<button type="button" onclick="document.getElementById('tips').src='files/md.php#image';">Image</button>
		<button type="button" onclick="window.open('files/', 'upload file', 'width=300,height=300');">Upload</button>

		</div>
		<button type="button" onclick="document.getElementById('tips').style.display = 'block';this.style.display='none';" style="width:100%;">Show formatting tips</button>

		<iframe id="tips" src="files/md.php" style="display:none;width:100%;"></iframe>
		<label for="description">Room description:</label><br />
		<textarea required="required" maxlength="5000" name="description" id="description" rows="15" style="width:100%;" oninput="document.getElementById('chars').innerHTML=this.value.length+'/5000 allowed characters';"></textarea>
		<div id="chars"><strong>Character limit:</strong> 5000</div>
		<button onclick="this.innerHTML='Please wait...';this.disabled='disabled';preview();this.disabled='';this.innerHTML='Preview';" type="button">Preview</button>
		<div id="preview" style="border:1px solid;padding:2px;">markdown preview here</div>
		<br />
		<input type="submit" value="Create Room" />
		<input type="reset" style="background-color:transparent !important;outline:none !important;border:none;color:white;" onclick="document.getElementById('chars').innerHTML = 'Discarded'" value="Discard" />
	</form>
	<script>
	function preview() {
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "libraries/parsemd.php");
	let formdata = new FormData(document.getElementById('form'));
    xhr.send(formdata);

    xhr.onload = function () {
        if (xhr.status != 200) {
			throw new Error('error');
			document.getElementById('preview').innerHTML = "<span style=\"color:red;\">Error "+xhr.status+"!</span>"
        } else {
			var text = xhr.responseText;
			document.getElementById('preview').innerHTML='';
			document.getElementById('preview').innerHTML=text;
        }
    };
    xhr.onerror = function () {
        alert("Request failed");
    };
}
</script>