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
	<?php
	include_once("./public/header.php");
	include_once('./styles/inject.php');
	if (!isset($_GET['topic'], $_GET['post'])) die("No topic or post specified");
	if (!isset($_COOKIE['login'])) die("You must be the author of a thread to mark a post as an answer"); ?>
    <title>Mark post as answer</title>
	</head>
	<body>
	<?php 
		$topic = $_GET['topic'];
		$post = $_GET['post'];
		$dir = "data/messages/" . cleanFilename($topic);
		if (!is_dir("data/messages/" . cleanFilename($topic))) die("Room name invalid.");
		$config = json_decode(file_get_contents($dir . "/config.json"));
if ($config->author !== getname()) die("Access denied.");
		$msg = json_decode(file_get_contents($dir . "/msg.json"));
		if (!isset($msg->$post)) die("Invalid post.");
		if ($config->accepted == $post) {
			unset($config->accepted);
			?>The chosen answer is no longer chosen.<?php
		} else $config->accepted = $post;
		if (isset($config->accepted)) {
			?>You have chosen an answer.<?php
		}
			?> <a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($topic)); ?>">Return to topic</a><?php
fwrite(fopen($dir . "/config.json", "w+"), json_encode($config));
		?>