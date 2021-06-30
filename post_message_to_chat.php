<?php
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