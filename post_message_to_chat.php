<?php
$message = htmlspecialchars($_POST['message']);
$message = str_replace("\n", "<br>", $message);
$name = $_POST['login'];
$pointer = fopen('data/messages/webchat.txt', 'a+');
$text = '<div style="border:1px solid black;">'.$name.'<div style="background-color:gray;">'.$message.'</div></div>';
fwrite($pointer, $text);
?>