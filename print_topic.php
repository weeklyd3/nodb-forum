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
    <title>Print Topic</title>
	<?php
	?>
  </head>
  <body>
	<?php 
	require_once __DIR__ . '/libraries/lib.php';
	if (!isset($_GET['title'])) die("Specify title to print");
	if (!file_exists(__DIR__ . '/data/messages/' . cleanFilename($_GET['title']) . '/config.json')) die("Bad title");
	$title = $_GET['title'];
	$config = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($_GET['title']) . '/config.json'));
	?>
	<style>@media print {
		#return-link {
			display: none;
		}
	}
	</style>
	<h1><?php echo htmlspecialchars($title); ?></h1>
	<p>This is a printable version of the topic. <a id="return-link" href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($title)); ?>">Return to web version</a></p>
	<h2>Tags</h2>
	<p>Tagged: 
	<?php 
	foreach (explode(" ", $config->tags) as $tag) {
		echo htmlspecialchars($tag), " ";
	}
	?></p>
	<h2>This Topic</h2>
	<ol>
		<li><strong><?php echo htmlspecialchars($config->author); ?></strong>
		<blockquote style="border-left:5px solid;margin:15px;padding-left:15px;">
			<?php echo $config->description_html; ?>
		</blockquote></li>
	<?php 
		$msgs = (array) json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($title) . '/msg.json'));
		foreach ($msgs as $msg) {
			?><li><strong><?php echo file_exists(__DIR__ . '/data/accounts/' . cleanFilename($msg->author) . '/psw.txt') ? htmlspecialchars($msg->author) : "&lt;USER REMOVED>"; ?></strong>
			<blockquote style="border-left:5px solid;margin:15px;padding-left:15px;">
			<?php echo $msg->html; ?>
			</blockquote></li><?php
		}
	?>