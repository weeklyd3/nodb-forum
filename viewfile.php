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
    <title>View File</title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
  </head>
  <body>
	  <p><a href="files/directory.php">Back to All files</a></p>
			  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
<label>Enter a filename to view the file:
	<input type="text" name="filename" id="filename" />
</label>
				  
<input type="submit" value="View file!" />
				  
<input type="submit" name="viewinfo" value="View file information!" />
<script src="styles/other/picker.js"></script>
<link rel="stylesheet" href="styles/other/picker.css" />
<script>
globalThis.noOpenButton = true;	initFilePicker(document.getElementById('filename'));</script>
</form>
  <h2><?php 
  function toolbar($name) {
	  ?><div class="toolbar-wrapper" style="color: black;">
	  	<?php
		  $fileMetadata = file_exists(__DIR__ . '/file_details.json') ? json_decode(file_get_contents(__DIR__ . '/file_details.json')) : json_decode("{}");
		  if (isset($fileMetadata->$name)) { ?> 
		  <div style="color: black;">
		  <?php 
		  if (isset($_GET['viewinfo'])) {
		  ?>
		  Viewing file information (<a href="viewfile.php?filename=<?php echo htmlspecialchars(urlencode($name)); ?>">view file</a>)
		  <?php } else {
		  ?>
		  Viewing file (<a href="viewfile.php?filename=<?php echo htmlspecialchars(urlencode($name)); ?>&viewinfo=true">view file information</a>)<?php
		    } ?>
		  </div><?php 
		  }
		?>
	  	<div class="name"><?php echo htmlspecialchars($name); ?> (<code><?php echo filesize($name); ?></code> bytes) <span class="float-right"><a download="<?php echo htmlspecialchars($name); ?>" href="files/download.php?filename=<?php echo htmlspecialchars($name); ?>">Download</a> <a href="files/download.php?filename=<?php echo htmlspecialchars($name); ?>" target="_blank">Open in new tab</a></span></div>
		  <div class="file-contents"><?php 
		  return true;
  }
  if (!isset($_GET['filename'])) die("No file name</h2>Please specify a filename.");
  $n = cleanFilename($_GET['filename']);
  chdir("files/uploads");
  if (!file_exists($n)) die("Bad name</h2>Bad filename");
  echo htmlspecialchars($n); ?></h2>
  <p><a href="files/download.php?filename=<?php echo htmlspecialchars($n); ?>">Original file (click and press <kbd>Ctrl/Command</kbd> + <kbd>S</kbd> to download)</a></p>
  <p>Type: <?php 
  $type = mime_content_type($n);
  echo htmlspecialchars($type); ?></p><?php if (!isset($_GET['viewinfo'])) { ?><p>This file was detected as: <?php }
  if (isset($_GET['viewinfo'])) {
	  $fileMetadata = file_exists(__DIR__ . '/file_details.json') ? json_decode(file_get_contents(__DIR__ . '/file_details.json')) : json_decode("{}");
	  toolbar($n);
	  if (!isset($fileMetadata->$n)) {
		  ?>No file info available.<?php
	  } else {
		  $filedata = $fileMetadata->$n;
		  ?>
		  <dl>
		  <dt>
		  Uploader</dt>
		  <dd>
		  <?php echo htmlspecialchars($filedata->uploader); ?>
		  </dd>
		  <dt>Licensing</dt>
		  <dd>
		  <?php echo htmlspecialchars($filedata->license); ?>
		  </dd>
		  <dt>More information about license</dt>
		  <dd><?php echo htmlspecialchars($filedata->extendedLicense); ?></dd>
		  <dt>File details</dt>
		  <dd><?php
		  $Parsedown = new Parsedown;
		  echo $Parsedown->text($filedata->details); ?></dd>
		  </dl>
		  <?php
	  }
	  ?></div></div><?php
  } else {
  if (startsWith($type, "image/")) {
	  ?>image.</p>
	  <?php $GLOBALS['t'] = toolbar($n); ?>
	  <img src="files/download.php?filename=<?php echo htmlspecialchars(urlencode($n)); ?>" alt="User-uploaded image" class="viewer" /><?php
  }
  if (startsWith($type, "audio/")) {
	  ?>audio.</p>
	  <figcaption>You can play the file:</figcaption>
	  <?php $GLOBALS['t'] = toolbar($n); ?>
	  <audio controls="controls" src="files/download.php?filename=<?php echo htmlspecialchars(urlencode($n)); ?>">
	  Oh no! Browser
	  not supported. Try downloading and opening with a 
	  music player program.</audio><?php
  }
  if (startsWith($type, "text/")) {
	  require 'libraries/hljs_langs.php';
	  ?>text.</p>
	  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
	  <label>Syntax highlighting incorrect? Enter the Highlight.js language ID and then click submit: 
	  <input type="text" name="lang" />
	  </label>
	  <input type="hidden" name="filename" value="<?php echo htmlspecialchars($n); ?>" />
	  <input type="submit" />
	  </form>
	  <?php $GLOBALS['t'] = toolbar($n); ?>
	  <pre><code<?php if (isset($_GET['lang'])) { 
		  if (in_array($_GET['lang'], $languages)) { ?> class="language-<?php 
	  echo htmlspecialchars($_GET['lang']);
	  ?>"<?php } } ?>><?php echo htmlspecialchars(file_get_contents($n)); ?></code></pre>
	  <?php
  }
  if (startsWith($type, "video/")) {
	  ?>a video.</p>
	  <?php 
	  $GLOBALS['t'] = toolbar($n, true); ?>
	  <video controls="controls">
	  <source src="files/download.php?filename=<?php echo htmlspecialchars(urlencode($n)); ?>" />
	  Oh no! Browser not supported. Try downloading and opening with a video player.</video><?php
  }
  ?><style>.file-contents { overflow: scroll; max-height: 90vh; }</style><?php
  if (isset($GLOBALS['t'])) { ?></div></div><?php }
  else { ?>File type not supported. Click the link above to download the file.<?php } }