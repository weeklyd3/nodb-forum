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
?><html lang="en">
  <head>
    <title>Forums &mdash; Create Chat Room</title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
  </head>
  <body>
  <?php 
	blockCheck();
  if (!isset($_COOKIE['login'])) {
	  echo 'You need to log in to create rooms.';
	  include_once('./public/footer.php');
	  exit(0);
  }
		if (isset($_POST['roomtitle'])) {
			$parsedown = new Parsedown();
			class Room {
				function __construct($message) {
					echo $message;
				}
				public $title = null;
				public $description = null;
				public $description_html = null;
				public $author = null;
				public $creationTime = null;
				public $tags = array();
				public $flags = null;
			}
			$room = new Room("-<span></span>-> constructed template");
			$room->title = $_POST['roomtitle'];
			$room->description = $_POST['description'];
			$room->description_html = $parsedown->text($room->description);
			$room->author = getname();
			$room->creationTime = time();
			$room->tags =implode(" ", array_map('strtolower', array_filter(array_unique(explode(" ", $_POST['tags'])))));

			$exist = json_decode(file_get_contents('config.json'))->tags;
			foreach (array_map('strtolower', array_filter(array_unique(explode(" ", $_POST['tags'])))) as $key => $value) {
				if (!in_array($value, $exist)) {
					array_push($exist, $value);
				}
			}

			$decode = json_decode(file_get_contents('config.json'));
			$decode->tags = $exist;
			fwrite(fopen('config.json', 'w+'), json_encode($decode));

			echo '<h2>';
			echo htmlspecialchars($room->title) . ' is being created...</h2>';
			logmsg("Topic created", "A topic was created", getname());
			echo '<pre>'.htmlspecialchars(json_encode($room)).'</pre>';
			if (!file_exists('data/messages/'.cleanFilename($room->title).'/config.json')) {
				$dir = mkdir('data/messages/'.cleanFilename($room->title));
				if ($dir) {
					$filename = 'data/messages/'.cleanFilename($room->title) . '/config.json';
					$handle = fopen($filename, 'w+');
					$one = fwrite($handle, json_encode($room));
					$two = fwrite(fopen(__DIR__ . '/data/messages/'.cleanFilename($room->title).'/msg.json', 'w+'), "{}");
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
    	<strong>WARNING: Only create new rooms if you absolutely need to!</strong> If the topic already exists, do not re-post it.
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
			<b><a href="files" target="_blank">Button not working?</a></b>

		</div>
		<button type="button" onclick="document.getElementById('tips').style.display = 'block';this.style.display='none';" style="width:100%;">Show formatting tips</button>

		<iframe id="tips" src="files/md.php" style="display:none;width:100%;"></iframe>
		<label for="description">Room description:</label><br />
		<textarea required="required" maxlength="30000" name="description" id="description" rows="15" style="width:100%;" oninput="document.getElementById('chars').innerHTML=this.value.length+'/30000 allowed characters';"></textarea>
		<div id="chars"><strong>Character limit:</strong> 30000</div>
		<button id="previewButton" onclick="this.innerHTML='Please wait...';this.disabled='disabled';preview();" type="button">Preview</button>
		<div id="preview" style="border:1px solid;padding:2px;">markdown preview here</div>
		<br />
		<label>Enter your tags separated by spaces:
		<input required="required" type="text" style="width: 100%;" name="tags" placeholder="Add your tags" />
		</label>
		<details>
			<summary>What to do to get a response faster</summary>
			<ul>
				<li>Write a descriptive title. This is how NOT to write a title: "I NEED HELP!!! THIS IS URGENT!!!"</li>
				<li>Search first, using first the site search engine and then the search box in your browser. Many common issues have already been asked somewhere else, maybe on another site.</li>
				<li>This is not the place to post test topics and have them stick around. <a href="files/md_sandbox.php">You may test your formatting here, but it is never saved.</a></li>
				<li>Document what you've already tried and searched and why it didn't work.</li>
				<li>Make sure your topic is on-topic. If your topic is on-topic, more people will know how to solve it.</li>
				<li>Tag the topic with the related tags. If related tags do not exist, then type them in anyways; they will get created when the topic is saved. People expert in the related tags will be more likely to see and reply to your topic. Avoid tags like "urgent" or "beginner" that do not describe the nature of the problem.</li>
				<li>Lastly, if your topic does not get a response, maybe it's missing some required information. Ask a friend to read it and suggest improvements, and add new information using the "edit" link below the topic. If people do not have all the necessary information, they will not be able to answer.</li>
			</ul>
		</details>
		<details>
			<summary>Show all tags</summary>
			<?php 
				$stuff = file_get_contents('config.json');
				$object = json_decode($stuff);
				foreach ($object->tags as $key => $value) {
					?><span class="tag"><?php echo htmlspecialchars($value); ?></span> <?php
				}
			?>
		</details>
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
			document.getElementById('preview').innerHTML = "<span style=\"color:red;\">Error "+xhr.status+"!</span>";

        } else {
			var text = xhr.responseText;
			document.getElementById('preview').innerHTML='';
			document.getElementById('preview').innerHTML=text;
			hljs.highlightAll();
			document.getElementById('previewButton')
				.setAttribute(
					'style',
					'color:white;background-color:#00ff00;'
				);
			document.getElementById('previewButton').innerHTML = 'Done';
			setTimeout(function() {
				document.getElementById('previewButton').removeAttribute('style');
				document.getElementById('previewButton').innerHTML = 'Preview';
				document.getElementById('previewButton').disabled = '';
			}, 667);
        }
    };
    xhr.onerror = function () {
        alert("Request failed");
    };
}
</script>
