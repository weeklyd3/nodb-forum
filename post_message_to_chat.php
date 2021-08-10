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
include('./libraries/lib.php');
include('./libraries/parsedown.php');
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
	$pointer = fopen('data/messages/'.cleanFilename($room).'/webchat.txt', 'a+');
	if (!file_exists('data/messages/'.cleanFilename($room).'/webchat.txt')) {
		touch('data/messages/'.cleanFilename($room).'/webchat.txt', 0777);
	}
	putenv("TZ=UTC");
	$raw = $Parsedown->text($message);
	$parsedHTML = html_entity_decode($raw);
	$parsedHTML = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $parsedHTML);
	$time = date("h:i:s m/d/20y");
	$time.=' UTC';
	$time = substr($time, 0, 23);
	$text = '<div style="border:1px solid black;"><a href="account/viewuser.php?user='.htmlspecialchars(explode("\0", $_COOKIE['login'])[0]).'"><img src="'.$image.'" alt="Avatar"> '.$name.'</a><br>on '.$time.'<br><span class="message">'.$parsedHTML.'</pre><!--comment--></blockquote></span><div style="background-color:#00dddd;">Attachment: '.$GLOBALS['attach'].'</div></div>';
	$write = fwrite($pointer, $text);
	if ($write) {
		echo '{"status":true}';
	} else {
		echo '{"status":null}';
	}
} else {
	echo '{"status":false}';
}
?>