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
	if (!isset($_POST['submit'], $_POST['contents'])) { ?>
	Write your reply below: <?php } 
	if (isset($_POST['submit'], $_POST['contents'])) {
		if ($_POST['submit'] == 'Preview') { ?>
			Preview:<br /><?php 
			$Parsedown = new Parsedown;
			echo $Parsedown->text($_POST['contents']); 
		}
		if ($_POST['submit'] == 'Submit') {
			include_once('./libraries/lib.php');
include_once('./libraries/parsedown.php');
$Parsedown = new Parsedown;
$message = $_POST['contents'];
$room = $_GET['room'];
$name = explode("\0", $_COOKIE['login'])[0];
$attach = cleanFilename($_POST['attach']);
	putenv("TZ=UTC");
	$raw = $Parsedown->text($message);
	$parsedHTML = html_entity_decode($raw);
	$parsedHTML = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $parsedHTML);
	$time = date("Y-m-d H-i-s", time());
	$time.=' UTC';
	$name = getname() . time();
	$Parsedown = new Parsedown;
	class msg {
		public $author = null;
		public $md = null;
		public $time = null;
		function __construct($author, $md, $time, $attach) {
			$this->author = $author;
			$this->md = $md;
			$this->time = $time;
			$this->attach = $attach;
			$Parsedown = new Parsedown;
			$this->html = $Parsedown->text($this->md);
		}
	}
	$json = json_decode(file_get_contents(__DIR__ . '/data/messages/'.cleanFilename($_GET['room']) . '/msg.json'));
	$json->$name = new msg(getname(), $_POST['contents'], time(), $_POST['attach']);
	if ($_POST['reply'] != '')
		$json->$name->reply = $_POST['reply'];

	if ($_POST['reply'] != '') {
		if (file_exists(__DIR__ . '/data/accounts/' . cleanFilename($_POST['reply']) . '/user.txt')) {
			if (!file_exists(__DIR__ . '/data/accounts/' . cleanFilename($_POST['reply']) . '/inbox.json')) {fwrite(fopen(__DIR__ . '/data/accounts/' . cleanFilename($_POST['reply']) . '/inbox.json', 'w+'), '{"items":[]}');}

			$inbox = json_decode(file_get_contents(__DIR__ . '/data/accounts/' . cleanFilename($_POST['reply']) . '/inbox.json'));
			$item = new stdClass;
			$item->time = time();
			$item->text = getname() . ' replied to your post in ' . $_POST['room'];
			$item->read = false;
			$item->type = "Chat reply";
			if ($inbox->items == null) $inbox->items = array();
			$item->url = 'webchat.php?room=' . urlencode($_POST['room']);
			array_push($inbox->items, $item);
			fwrite(fopen(__DIR__ . '/data/accounts/' . cleanFilename($_POST['reply']) . '/inbox.json', 'w+'), json_encode($inbox));
		}
	}
	$pointer = fopen('data/messages/'.cleanFilename($room).'/msg.json', 'w+');
	$write = fwrite($pointer, json_encode($json));
		}
	}
	?></div>
	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<ul style="list-style:none;padding:0;">
		<li><label>Reply contents:<br /><textarea name="contents" rows="20" style="width:100%;"><?php if (isset($_POST['contents'])) { echo htmlspecialchars($_POST['contents']); } ?></textarea></label></li>
		<li><label>Reply to: <input type="text" name="reply" /></label></li>
		<li><label>Reply attachment (<a href="files/">upload</a>: <input type="text" name="attach" /></label></li>
		<li><input type="submit" name="submit" value="Preview" /> 
		<input type="submit" name="submit" value="Submit" style="font-weight:bold;" /></li>
	</ul>
	</form>