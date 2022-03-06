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
?><html lang="en" style="background-color: white; background: white;">
  <head>
    <title>Print Topic</title>
	<?php
require 'styles/inject.php';
	?>
  </head>
  <body style="background-color: white; background: white; color: black;">
	<?php 
	require_once __DIR__ . '/libraries/lib.php';
	if (!isset($_GET['title'])) die("Specify title to print");
	if (!file_exists(__DIR__ . '/data/messages/' . cleanFilename($_GET['title']) . '/config.json')) die("Bad title");
	$title = $_GET['title'];
	$config = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($_GET['title']) . '/config.json'));
	?>
	<style>@media print {
		.return-link, #print-link {
			display: none !important;
		}
	}
	#print-link {
		display: none;
	}
	</style>
	<h1><?php echo htmlspecialchars($title); ?></h1>
	  <p>From <?php 
$forumconfig = json_decode(file_get_contents('config.json'));
echo htmlspecialchars($forumconfig->forumtitle); ?> (<i>http://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?></i>)</p>
	  <p>This topic can be found online at <i>http://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>/viewtopic.php?room=<?php echo htmlspecialchars(urlencode($config->title)); ?></i></p>
	<p class="return-link">This is a printable version of the topic. <a class="return-link" href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($title)); ?>">Return to web version</a>
	<a class="return-link" id="print-link" href="javascript:;">Print now</a><br />
	<span class="return-link smaller">
	(Don't worry, neither this nor any of the links above will be printed!)</span>
	<script>
	document.querySelector('#print-link').style.display = 'inline';
	document.querySelector('#print-link').addEventListener('click', function() {
		window.print();
	});
	</script>
	</p>
	<h2>Tags</h2>
	<p>Tagged: 
	<?php 
	foreach (explode(" ", $config->tags) as $tag) {
		echo htmlspecialchars($tag), " ";
	}
	?></p>
	<h2>This Topic</h2>
		<h3><?php userlink($config->author); ?> on <?php 
		echo friendlyDate($config->creationTime); ?></h3>
	  <div style="border:1px solid;padding:2px;">
			<?php echo $config->description_html; ?></div>
	<?php 
		$msgs = (array) json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($title) . '/msg.json'));
		foreach ($msgs as $msg) {
			?>
			<h3><?php userlink($msg->author); ?> on <?php echo friendlyDate($msg->time); ?></h3>
	  <div style="border: 1px solid; padding: 2px;">
			<?php echo $msg->html; ?></div><?php
		}
	?>
	<hr />
	<p>End of topic</p>