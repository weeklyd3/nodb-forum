<?php 
if (!isset($_GET['filename'])) {
	die("No filename specified!");
}
require '../libraries/lib.php';
header("Content-Type: " . mime_content_type("uploads/" . cleanFilename($_GET['filename'])));
readfile("uploads/" . cleanFilename($_GET['filename']));
?>