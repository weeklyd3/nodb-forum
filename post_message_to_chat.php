<?php
/*
    Forum Software
    Copyright (C) 2021 weeklyd3

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
?><?php
header('Content-Type: text/plain');
$message = htmlspecialchars($_POST['message']);
$name = $_POST['login'];
if ($name) {
	$pointer = fopen('data/messages/webchat.txt', 'a+');
	$text = '<div style="border:1px solid black;">'.$name.'<div style="background-color:gray;">'.$message.'</div></div>';
	echo fwrite($pointer, $text);
} else {
	echo false;
}
?>