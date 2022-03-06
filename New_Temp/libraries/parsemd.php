<<<<<<< HEAD
<?php
require('parsedown.php');
require 'lib.php';
$Parsedown = new Parsedown();
if (isset($_POST['description'])) {
	echo $Parsedown->text($_POST['description']);
} else {
	if ($_GET['text']) {
		echo $Parsedown->text($_GET['text']);
	} else {
		if ($_POST['message']) {
			echo $Parsedown->text($_POST['message']);
		}
	}
}
=======
<?php
require('parsedown.php');
require 'lib.php';
$Parsedown = new Parsedown();
if (isset($_POST['description'])) {
	echo $Parsedown->text($_POST['description']);
} else {
	if ($_GET['text']) {
		echo $Parsedown->text($_GET['text']);
	} else {
		if ($_POST['message']) {
			echo $Parsedown->text($_POST['message']);
		}
	}
}
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
?>