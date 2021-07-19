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
$message = removeScriptTags($_POST['message']);
$room = $_POST['room'];
$name = $_POST['login'];
if ($name) {
	$pointer = fopen('data/messages/'.cleanFilename($room).'/webchat.txt', 'a+');
	if (!file_exists('data/messages/'.cleanFilename($room).'/webchat.txt')) {
		touch('data/messages/'.cleanFilename($room).'/webchat.txt', 0777);
	}
	putenv("TZ=UTC");
	$time = date("h:i:s m/d/20y");
	$time.=' UTC';
	$time = substr($time, 0, 23);
	$text = '<div style="border:1px solid black;">'.$name.' on '.$time.'<br /><span class="message" style="background-color:gray;font-family:inherit;font-size:17;background-color:transparent !important;color:white !important;">'.$message.'</span></div>';
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