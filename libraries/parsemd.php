<?php
include('parsedown.php');
$Parsedown = new Parsedown();
if ($_POST['description']) {
	echo $Parsedown->text($_POST['description']);
} else {
	echo $Parsedown->text($_GET['text']);
}
?>