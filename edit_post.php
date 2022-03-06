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
    <title>Edit Post</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	require 'libraries/editlib.php';
	require_once 'libraries/parsedown.php';
$Parsedown = new Parsedown;
	if (!isset($_GET['topic'])) die("Specify topic first");
	if (!getname()) die("Please log in to edit");
	if (!file_exists(__DIR__ . '/data/messages/'.cleanFilename($_GET['topic']).'/msg.json')) die("Bad title");
	$config = json_decode(file_get_contents(__DIR__ . '/data/messages/'.cleanFilename($_GET['topic']).'/msg.json'));
	$post = $_GET['post'];
	if (!isset($config->$post)) die("Post not found");
	$postcontent = $config->$post;
	if ((verifyAdmin() == false) && (getname() != $postcontent->author)) die("access denied");
		if (isset($_POST['contents'])) {
			$original = new Revision($postcontent->md, $postcontent->author, $postcontent->time, "Original revision");
			$postcontent->html = $Parsedown->text($_POST['contents']);
			$postcontent->md = $_POST['contents'];
			$rev = new Revision($_POST['contents'], getname(), time(), $_POST['summary']);
			if (!isset($postcontent->revisions)) $postcontent->revisions = array($original);
			array_push($postcontent->revisions, $rev);
			$config->$post = $postcontent;
			fwrite(fopen(__DIR__ . '/data/messages/'.cleanFilename($_GET['topic']).'/msg.json', 'w+'), json_encode($config));
			?><p>Edit saved. <a href="viewtopic.php?room=<?php echo htmlspecialchars($_GET['topic']); ?>">Return to room?</a></p><?php
		}
	?>
  </head>
  <body>
  <h2>Edit</h2>
  <p>Only edit your comment if the original intent is preserved. For another reply or to comment, add another comment.</p>
  <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
  <label>Enter contents of post:<br />
  <textarea rows="5" cols="50" name="contents"><?php
	echo htmlspecialchars($postcontent->md);
  ?></textarea></label><br />
  <label>Enter edit summary:<br /><input type="text" required="required" name="summary" /></label>
  <br />
  <input type="submit" value="Save" />
  </form>