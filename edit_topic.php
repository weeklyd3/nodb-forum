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
    <title>Edit Topic</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	require './libraries/editlib.php';
	if (!isset($_GET['name'])) die("Specify room first");
	if (!getname()) die("Please log in to edit");
	if (!file_exists(__DIR__ . '/data/messages/'.cleanFilename($_GET['name']).'/config.json')) die("Bad title");
	$config = json_decode(file_get_contents(__DIR__ . '/data/messages/'.cleanFilename($_GET['name']).'/config.json'));
	if ((verifyAdmin() == false) && (getname() != $config->author)) die("access denied");
	?>
  </head>
  <body>
  <?php 
  	if (isset($_POST['body'])) {
		if ($config->description != $_POST['body']) {
			if (!isset($config->revisions)) {
				$config->revisions = array();
				$config->revisions[0] = new Revision($config->description, $config->author, $config->creationTime, "Original version");
			}

			array_push($config->revisions, new Revision($_POST['body'], getname(), time(), $_POST['summary']));

			$Parsedown = new Parsedown;
			$config->description_html = $Parsedown->text($_POST['body']);

			$config->description = $_POST['body'];

			fwrite(fopen(__DIR__ . '/data/messages/'.cleanFilename($_GET['name']).'/config.json', 'w+'), json_encode($config));
			?>Your edit has been saved. <a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($_GET['name'])); ?>">Return to topic</a> <?php
		} else {
			?>You forgot to make changes!<hr /><?php
		}
	}
		?>
	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<label>Edit topic:<br />
	<textarea name="body" rows="15" style="width:100%;"><?php echo htmlspecialchars($config->description); ?></textarea></label>
	<br />
	<label>Edit summary: <input required="required" type="text" name="summary" /></label>
	<br />
	<input type="submit" value="Save edits" />
	</form>
	