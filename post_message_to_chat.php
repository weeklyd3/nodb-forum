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
header('Content-Type: application/json');
include_once('./libraries/lib.php');
include_once('./libraries/parsedown.php');
$Parsedown = new Parsedown;
$message = $_POST['message'];
$room = $_POST['room'];
$name = explode("\0", $_COOKIE['login'])[0];
$attach = cleanFilename($_POST['attach']);
$image = 'data/accounts/'.cleanFilename($name).'/avatar.png';
if ($attach == "") {
	$GLOBALS['attach'] = 'none!';
} else {
	if (file_exists('files/uploads/'.$attach)) {
		$GLOBALS['attach'] = htmlspecialchars($attach) . ' (<a href="files/uploads/'.htmlspecialchars($attach).'" download="">download at your own risk!</a>) (<a href="files/uploads/'.htmlspecialchars($attach).'" target="_blank">view raw</a>)';
	} else {
		$GLOBALS['attach'] = 'File not found. Ask the sender!';
	}
}
if ($name) {
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
	$json = json_decode(file_get_contents(__DIR__ . '/data/messages/'.cleanFilename($_POST['room']) . '/msg.json'));
	$json->$name = new msg(getname(), $_POST['message'], time(), $_POST['attach']);
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
			$item->url = 'viewtopic.php?room=' . urlencode($_POST['room']);
			array_push($inbox->items, $item);
			fwrite(fopen(__DIR__ . '/data/accounts/' . cleanFilename($_POST['reply']) . '/inbox.json', 'w+'), json_encode($inbox));
		}
	}
	$pointer = fopen('data/messages/'.cleanFilename($room).'/msg.json', 'w+');
	$p = json_decode(file_get_contents('data/messages/'.cleanFilename($room).'/config.json'));
	$p->replies++;
	fwrite(fopen('data/messages/'.cleanFilename($room).'/config.json', 'w+'), json_encode($p));
	$write = fwrite($pointer, json_encode($json));
	$search = fopen(__DIR__ . '/data/messages/'.cleanFilename($_POST['room']).'/webchat.txt', 'a+');
	fwrite($search, "<div>".htmlspecialchars(getname())." on ".date("Y:m:d H:i:s", time()).":<div>".$Parsedown->text($_POST['message'])."</div>with attachment &quot;".$_POST['attach']."&quot;</div>");
	if ($write) {
		echo '{"status":true}';
	} else {
		echo '{"status":null}';
	}
	$queue = json_decode(file_get_contents("review/items.json"));
	array_push($queue->items, array("room" => $_POST['room'], "message" => $name));
	fwrite(fopen("review/items.json", "w+"), json_encode($queue));
} else {
	echo '{"status":false}';
}
if (!isset($_POST['js'])) header("Location: viewtopic.php?room=" . urlencode($_POST['room']));
?>