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
			$contribsPath = __DIR__ . '/data/accounts/' . cleanFilename(getname()) . '/contribs.json';
			if (!file_exists($contribsPath)) $contribs = array();
			else $contribs = json_decode(file_get_contents($contribsPath));
			$contrib = new stdClass;
			$contrib->room = $_POST['roomtitle'];
			array_unshift($contribs, $contrib);
			fwrite(fopen($contribsPath, 'w+'), json_encode($contribs));
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
		<div class="box no-height-limit">
			<?php
			function editorOptions() {
				?>
		<ul class="options toolbar">
			<li><a href="create.php#bold"><img src="img/icons/editor/BoldIcon.png" alt="Bold" /></a></li>
			<li><a href="create.php#italic"><img src="img/icons/editor/ItalicIcon.png" alt="Italic" /></a></li>
			<li><a href="create.php#bolditalic"><img src="img/icons/editor/BoldItalicIcon.png" alt="Bold and Italic" /></a></li>
			&nbsp;
			<li><a href="create.php#script"><img src="img/icons/editor/SubSuperScriptIcon.png" alt="Super/Subscript" /></a></li>
			<li><a href="create.php#hr"><img src="img/icons/editor/HRIcon.png" alt="Horizontal rule" /></a></li>
			&nbsp;
			<li><a href="create.php#hyperlink"><img src="img/icons/editor/LinkIcon.png" alt="Hyperlink" /></a></li>
			<li><a href="create.php#image"><img src="img/icons/editor/ImageIcon.png" alt="Image" /></a></li>
			&nbsp;
			<li><a href="create.php#other"><img src="img/icons/editor/MoreIcon.png" alt="Other formatting" /></a></li>
		</ul><?php } 
editorOptions(); ?>

		<style>
			#formatting > * {
				display: none;
			}
			#bold:target, #italic:target, #bolditalic:target, #script:target, #hr:target, #code:target, #hyperlink:target, #image:target, #other:target {
				display: block;
			}
		</style>
			<?php require 'files/md.php'; ?>
<p>View another tip:</p>
			<?php editorOptions(); ?>
		</div>
		<label for="description">Room description:</label><br />
		<textarea required="required" maxlength="30000" name="description" id="description" rows="15" style="width:100%;" oninput="document.getElementById('chars').innerHTML=this.value.length+'/30000 allowed characters';"></textarea>
		<div id="chars"><strong>Character limit:</strong> 30000</div>
		<button id="previewButton" onclick="this.innerHTML='Please wait...';this.disabled='disabled';preview();" type="button">Preview</button>
		<div id="preview" style="border:1px solid;padding:2px;">markdown preview here</div>
		<br />
		<label>Enter your tags separated by spaces:
		<input required="required" type="text" style="width: 100%;" name="tags" placeholder="Add your tags" /></label><div id="tagsearchwrapper" hidden="hidden"><label>
			<script>
document.querySelector('#tagsearchwrapper').removeAttribute('hidden'); </script>
			<span hidden="hidden">Search for tags:</span> 
			<input style="width: 100%;" id="search4tags" placeholder="Search for tags..." autocomplete="off" /> 
		</label>
			<ul style="display: none; position: absolute; z-index: 3328; background-color: white; border: 1px solid; color: black; list-style: none; padding: 0; width: calc(100% - 18px); max-height: calc(50% - 18px); overflow-y: scroll;" id="tag-chooser">
				<li style="position: sticky; top: 0; background-color: white;" class="no-choose" id="no-choose">Tag chooser (<a onclick="document.getElementById('tag-chooser').style.display = 'none';" href="javascript:;">close</a>)</li>
			</ul>
			</div> 
	<?php if (isset($_POST['roomtitle'])) exit(0); ?>	<script>globalThis.tags=JSON.parse(<?php echo json_encode(json_encode($config->tags)); ?>);
			document.querySelector('#search4tags').value = '';
		document.querySelector('#search4tags').addEventListener('input', function() {
	  	document.getElementById('tag-chooser').style.display = 'block';
			var searchTerm = document.querySelector('#search4tags').value;
			var firstChild = document.querySelector('#tag-chooser').firstElementChild;
document.querySelector('#tag-chooser').innerHTML = '';
			document.querySelector('#tag-chooser').appendChild(firstChild);
	  if (searchTerm === '') return;
			var entriesAdded = 0;
			globalThis.tags.forEach(function(tag) {
				if (!tag.includes(searchTerm)) {
					return;
				}
	  entriesAdded++;
				var searchQueryEntry = document.createElement('li');
				
				searchQueryEntry.textContent = tag;
				searchQueryEntry.addEventListener('click', function() {
document.getElementById('tag-chooser').style.display = 'none';
	  document.getElementById('search4tags').value = '';
	  document.getElementById('search4tags').focus();
	  if (document.querySelector('[name=tags]').value !== "" && !document.querySelector('[name=tags]').value.endsWith(" ")) {
					document.querySelector('[name=tags]').value += " " + tag;
	  } else {
	  document.querySelector('[name=tags]').value += tag;
	  }
	  
				});
	  document.querySelector('#tag-chooser').appendChild(searchQueryEntry);
			});
	  if (entriesAdded === 0) {
	  var noResults = document.createElement('li');
	  noResults.classList.add('no-choose');
	  noResults.textContent = "No results. If you would like to create this tag, please click this message.";
	  if (!searchTerm.includes(" ")) {
	  noResults.addEventListener('click', function() {
	  var newTagName = (document.querySelector('[name=tags]').value !== "" && !document.querySelector('[name=tags]').value.endsWith(" ")) ? " " : "";
	  newTagName += searchTerm;
	  document.querySelector('[name=tags]').value += newTagName;
		  document.querySelector('#tag-chooser').style.display = 'none';
		  document.querySelector('#search4tags').value = '';
	  });
	  } else {
	  noResults.style.color = 'red';
	  noResults.textContent = 'Error: Tags cannot contain spaces.';
	  }
	  document.querySelector('#tag-chooser').appendChild(noResults);
	  }
		});
		</script>
		<style>
			#tag-chooser li:not(.no-choose):hover {
				background-color: blue;
				color: white;
				cursor: pointer;
			}
			#tag-chooser li.no-choose {
				color: gray;
			}
			#tag-chooser li {
				border: 1px solid;
				padding: 7px;
			}
		</style>
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
		<script>
			var toolbar = document.querySelectorAll('.toolbar a');
			for (var index = 0; index < toolbar.length; index++) {
				var item = toolbar[index];
				item.addEventListener('click', function(event) {
					event.preventDefault();
					window.location.href = event.currentTarget.href;
					document.querySelector('.toolbar').scrollIntoView();
				});
			}
		</script>
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
		  <form id="hidden-uploader" action="files" enctype="multipart/form-data" style="display: none;">
			  <label>Do not touch this field.
			  <input name="image" type="file" /></label>
			  <input type="hidden" name="details" value="This is an image that was pasted and uploaded. If this file only contains simple text or shapes, it is in the public domain." />
			  <input type="hidden" name="license" value="other" />
			  <input type="hidden" name="randomize" value="" />
		  </form>
      <script>
document.getElementById('description').addEventListener('paste', (e) => {
  document.querySelector('input[name=image]').files = e.clipboardData.files;
			if (!document.querySelector('input[name=image]').files[0]) return;
			if (document.querySelector('input[name=image]').files[0].type.split('/')[0] !== 'image') return;

			uploadPrompt();
	console.log(e.clipboardData);
	});
		function uploadPrompt() {
			loadFile();
			document.getElementById('uploadPrompt').style.display = 'block';
		}
		function uploadHiddenFile() {
			var request = new XMLHttpRequest();
			request.addEventListener('load', function() {
				var response = JSON.parse(this.responseText);
				nodbForum.insertIntoInput(document.querySelector('textarea[id=description]'), "[![" + response.name + "](files/download.php?filename=" + encodeURIComponent(response.name) + ")](viewfile.php?filename=" + encodeURIComponent(response.name) + ")");
				document.getElementById('uploadPrompt').style.display = 'none';
				document.querySelector('input[id=uploadButton]').removeAttribute('disabled');
				document.querySelector('[id=uploadButton]').value = "Upload";
				document.querySelector('[id=uploadCancel]').removeAttribute('disabled');
			});
			request.open('POST', 'api/files/upload.php', true);
			request.send(new FormData(document.querySelector('#hidden-uploader')));
		}
  var loadFile = function(event) {
    var output = document.getElementById('previewimg');
    output.src = URL.createObjectURL(document.querySelector('[name=image]').files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src);
    }
  };
</script>
	  <div class="blanket" id="uploadPrompt" style="display: none;width:100vw;height:100vw;">
	<div class="overlay" style="display:block;">
		<h2>Upload image?</h2>
		
			<figure style="float: right;">
    <img id="previewimg" alt="Preview image" style="max-width: 50%;" />
    <figcaption>This image will be uploaded if you continue.</figcaption>
</figure>
		<p>To insert this image, it will be uploaded to the server. Please make sure this image has no sensitive information, as it will be available publicly.</p>
		<p>The Markdown code to embed the image will be automatically inserted after the image has been uploaded.</p>

		<input id="uploadButton" type="button" value="Upload" onclick="this.value = 'Uploading...'; document.getElementById('uploadCancel').disabled = 'disabled'; this.disabled = 'disabled'; uploadHiddenFile();" />
		<input type="button" id="uploadCancel" value="Cancel" onclick="document.getElementById('uploadPrompt').style.display = 'none';"
 />
	</div>
</div>