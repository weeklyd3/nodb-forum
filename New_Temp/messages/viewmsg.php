<<<<<<< HEAD
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
    <title><?php
require '../libraries/lib.php';
if (file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json')) {
	$msgs = json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json'));
	$id = isset($_GET['id']) ? $_GET['id'] : null;
	if (isset($msgs->$id)) {
		echo htmlspecialchars($msgs->$id->subject);
	} else { ?>Private Messages<?php }
} else { 
?>Private Messages<?php } ?></title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	if (!isset($_GET['id'])) die("Bad ID");
	if (!isset($_COOKIE['login'])) die("Bad login");
	?>
  </head>
  <body>
	<?php
	if (!file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json')) die("You do not have any messages.");
	$m = json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json'));
	$id = $_GET['id'];
	if (!isset($m->$id)) die("Bad ID");
	$s = $m->$id;
	if (!in_array(getname(), $s->people)) die("Access is denied as the message was not sent to you.");
	?>
	<p><a href="messages/">Back to Messages Home</a></p>
	<h2><?php echo htmlspecialchars($s->subject); ?></h2>
	  <p>Message options:</p>
	  <ul class="options">
		  <li><a href="messages/delete.php?id=<?php echo htmlspecialchars(urlencode($_GET['id'])); ?>"><img src="img/icons/XIcon.png" alt="" /> Delete</a></li>
		  <li><a href="messages/forward.php?id=<?php echo htmlspecialchars(urlencode($_GET['id'])); ?>"><img src="img/icons/ForwardIcon.png" alt="" /> Forward</a></li>
	  </ul>
	  <p>View as Markdown: <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>&markdown=on">once</a> <a href="account/#mdmail">always</a></p>
	  <p><a href="messages/report.php?id=<?php echo htmlspecialchars(urlencode($_GET['id'])); ?>">Is this message inappropriate?</a></p>
	<ul>
		<li>Sent: <?php echo friendlyDate($s->time); ?></li>
		<li>From: <?php echo htmlspecialchars($s->from); ?></li>
		<li>To: <ul><?php 
			foreach ($s->people as $p) {
				?><li>
					<a href="account/viewuser.php?user=<?php echo htmlspecialchars(urlencode($p)); ?>"><?php echo htmlspecialchars($p); ?></a>
				</li><?php
			}
		?></ul></li>
	</ul>
	<pre><code<?php 
if (!isset($_GET['markdown']) && !file_exists("../data/accounts/" . cleanFilename(getname()) . "/mdmail.txt")) { echo ">" . htmlspecialchars($s->body); } else { $Parsedown = new Parsedown; echo " style=\"white-space:normal;\" class=\"nohighlight hljs\">" . $Parsedown->text($s->body); }?></code></pre>
	<?php 
	if (!in_array(getname(), $s->read)) {
		array_push($s->read, getname()); 
	}
	$m->$id = $s;
	fwrite(fopen(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json', 'w+'), json_encode($m));
=======
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
    <title><?php
require '../libraries/lib.php';
if (file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json')) {
	$msgs = json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json'));
	$id = isset($_GET['id']) ? $_GET['id'] : null;
	if (isset($msgs->$id)) {
		echo htmlspecialchars($msgs->$id->subject);
	} else { ?>Private Messages<?php }
} else { 
?>Private Messages<?php } ?></title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	if (!isset($_GET['id'])) die("Bad ID");
	if (!isset($_COOKIE['login'])) die("Bad login");
	?>
  </head>
  <body>
	<?php
	if (!file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json')) die("You do not have any messages.");
	$m = json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json'));
	$id = $_GET['id'];
	if (!isset($m->$id)) die("Bad ID");
	$s = $m->$id;
	if (!in_array(getname(), $s->people)) die("Access is denied as the message was not sent to you.");
	?>
	<p><a href="messages/">Back to Messages Home</a></p>
	<h2><?php echo htmlspecialchars($s->subject); ?></h2>
	  <p>Message options:</p>
	  <ul class="options">
		  <li><a href="messages/delete.php?id=<?php echo htmlspecialchars(urlencode($_GET['id'])); ?>"><img src="img/icons/XIcon.png" alt="" /> Delete</a></li>
		  <li><a href="messages/forward.php?id=<?php echo htmlspecialchars(urlencode($_GET['id'])); ?>"><img src="img/icons/ForwardIcon.png" alt="" /> Forward</a></li>
	  </ul>
	  <p>View as Markdown: <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>&markdown=on">once</a> <a href="account/#mdmail">always</a></p>
	  <p><a href="messages/report.php?id=<?php echo htmlspecialchars(urlencode($_GET['id'])); ?>">Is this message inappropriate?</a></p>
	<ul>
		<li>Sent: <?php echo friendlyDate($s->time); ?></li>
		<li>From: <?php echo htmlspecialchars($s->from); ?></li>
		<li>To: <ul><?php 
			foreach ($s->people as $p) {
				?><li>
					<a href="account/viewuser.php?user=<?php echo htmlspecialchars(urlencode($p)); ?>"><?php echo htmlspecialchars($p); ?></a>
				</li><?php
			}
		?></ul></li>
	</ul>
	<pre><code<?php 
if (!isset($_GET['markdown']) && !file_exists("../data/accounts/" . cleanFilename(getname()) . "/mdmail.txt")) { echo ">" . htmlspecialchars($s->body); } else { $Parsedown = new Parsedown; echo " style=\"white-space:normal;\" class=\"nohighlight hljs\">" . $Parsedown->text($s->body); }?></code></pre>
	<?php 
	if (!in_array(getname(), $s->read)) {
		array_push($s->read, getname()); 
	}
	$m->$id = $s;
	fwrite(fopen(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json', 'w+'), json_encode($m));
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
	?>