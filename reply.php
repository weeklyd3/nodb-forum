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
    <title>Post Reply</title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	if (!isset($_GET['room'])) die("Bad title");
	if (!isset($_COOKIE['login'])) die("Not logged in");
	if (!file_exists(__DIR__ . '/data/messages/' . cleanFilename($_GET['room']) . '/msg.json')) die("Bad title");
	?>
  </head>
  <body>
	<h2>Post a Reply</h2>
	<div><?php
	if (!isset($_POST['submit'], $_POST['message'])) { ?>
	Write your reply below: <?php } 
	if (isset($_POST['submit'], $_POST['message'])) {
		if ($_POST['submit'] == 'Preview') { ?>
			Preview:<br /><?php 
			$Parsedown = new Parsedown;
			echo $Parsedown->text($_POST['message']); 
		}
		if ($_POST['submit'] == 'Submit') {
			require 'post_message_to_chat.php';
		}
	}
	?></div>
	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<ul style="list-style:none;padding:0;"><input type="hidden" name="room" value="<?php echo htmlspecialchars($_GET['room']); ?>" />
		<li><label>Reply contents:<br /><textarea name="message" rows="20" style="width:100%;"><?php if (isset($_POST['contents'])) { echo htmlspecialchars($_POST['message']); } ?></textarea></label></li>
		<li><label>Reply to: <input type="text" name="reply" /></label></li>
		<li><label>Reply attachment (<a href="files/">upload</a>: <input type="text" name="attach" /></label></li>
		<li><input type="submit" name="submit" value="Preview" /> 
		<input type="submit" name="submit" value="Submit" style="font-weight:bold;" /></li>
	</ul>
	</form>